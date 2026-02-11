<?php

namespace App\Repositories;

use App\Interfaces\FamilyMemberRepositoryInterface;
use App\Models\FamilyMember;
use App\Models\HeadOfFamily;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection as SupportCollection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class FamilyMemberRepository implements FamilyMemberRepositoryInterface
{
    public function getAll(
        ?string $search,
        ?int $limit,
        bool $execute
    ): Collection {
        $query = FamilyMember::where(function ($query) use ($search) {
            // Apply search filter if provided
            if ($search) {
                $query->search($search);
            }
        });

        $query->latest();

        // Apply limit if provided
        if ($limit) {
            $query->take($limit);
        }

        if ($execute) {
            return $query->get();
        }

        return $query;
    }

    public function getAllPaginated(
        ?string $search,
        ?int $rowPerPage
    ): LengthAwarePaginator {
        $query = $this->getAll(
            $search,
            $rowPerPage,
            false
        );

        return $query->paginate($rowPerPage);
    }

    public function getById(
        string $id
    ): ?FamilyMember {
        $query = FamilyMember::where('id', $id)->with('headOfFamily');

        return $query->first();
    }

    public function getByHeadOfFamilyId(
        string $headOfFamilyId
    ): SupportCollection {
        return HeadOfFamily::with(['familyMembers', 'user'])
            ->where('id', $headOfFamilyId)
            ->get()
            ->map(function ($headOfFamily) {
                return [
                    'id' => $headOfFamily->id,
                    'name' => $headOfFamily->user?->name,
                    'occupation' => $headOfFamily->occupation,
                    'identity_number' => $headOfFamily->identify_number,
                    'age' => $headOfFamily->birth_date ? \Carbon\Carbon::parse($headOfFamily->birth_date)->age : null,
                    'family_members' => $headOfFamily->familyMembers->map(function ($member) {
                        return [
                            'id' => $member->id,
                            'name' => $member->user?->name,
                            'relation' => $member->relation,
                            'occupation' => $member->occupation,
                            'identity_number' => $member->identify_number,
                            'age' => $member->birth_date ? \Carbon\Carbon::parse($member->birth_date)->age : null,
                            'profile_picture' => asset('storage/' . $member->profile_picture),
                        ];
                    })
                ];
            });
    }

    public function create(
        array $data
    ): FamilyMember {
        DB::beginTransaction();

        try {
            $userRepository = new UserRepository();

            $user = $userRepository->create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => $data['password'],
            ]);


            $familyMember = new FamilyMember();
            $familyMember->head_of_family_id = $data['head_of_family_id'];;
            $familyMember->user_id = $user->id;
            $familyMember->profile_picture = $data['profile_picture']->store('assets/family-members', 'public');
            $familyMember->identify_number = $data['identify_number'];
            $familyMember->gender = $data['gender'];
            $familyMember->birth_date = $data['birth_date'];
            $familyMember->phone_number = $data['phone_number'];
            $familyMember->occupation = $data['occupation'];
            $familyMember->marital_status = $data['marital_status'];
            $familyMember->relation = $data['relation'];
            $familyMember->save();

            DB::commit();

            return $familyMember;
        } catch (\Exception $e) {
            DB::rollBack();

            throw new Exception($e->getMessage());
        }
    }

    public function update(
        string $id,
        array $data
    ): ?FamilyMember {
        DB::beginTransaction();

        try {
            $familyMember = FamilyMember::find($id);

            if (isset($data['profile_picture'])) {
                $oldProfilePicture = $familyMember->profile_picture;

                $familyMember->profile_picture = $data['profile_picture']->store('assets/family-members', 'public');

                if ($oldProfilePicture && Storage::disk('public')->exists($oldProfilePicture)) {
                    Storage::disk('public')->delete($oldProfilePicture);
                }
            }

            $familyMember->head_of_family_id = $data['head_of_family_id'] ?? $familyMember->head_of_family_id;
            $familyMember->user_id = $data['user_id'] ?? $familyMember->user_id;
            $familyMember->identify_number = $data['identify_number'] ?? $familyMember->identify_number;
            $familyMember->gender = $data['gender'] ?? $familyMember->gender;
            $familyMember->birth_date = $data['birth_date'] ?? $familyMember->birth_date;
            $familyMember->phone_number = $data['phone_number'] ?? $familyMember->phone_number;
            $familyMember->occupation = $data['occupation'] ?? $familyMember->occupation;
            $familyMember->marital_status = $data['marital_status'] ?? $familyMember->marital_status;
            $familyMember->relation = $data['relation'] ?? $familyMember->relation;

            $familyMember->save();

            DB::commit();

            return $familyMember;
        } catch (\Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage());
        }
    }

    public function delete(
        string $id
    ): bool {
        DB::beginTransaction();

        try {
            $familyMember = $this->getById($id);

            if ($familyMember->profile_picture && Storage::disk('public')->exists($familyMember->profile_picture)) {
                Storage::disk('public')->delete($familyMember->profile_picture);
            }

            $familyMember->delete();

            DB::commit();

            return true;
        } catch (\Exception $e) {
            DB::rollBack();

            throw new Exception($e->getMessage());
        }
    }
}
