<?php

namespace App\Repositories;

use App\Interfaces\HeadOfFamilyRepositoryInterface;
use App\Models\HeadOfFamily;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class HeadOfFamilyRepository implements HeadOfFamilyRepositoryInterface
{
    public function getAll(
        ?string $search,
        ?int $limit,
        bool $execute
    ): Collection {
        $query = HeadOfFamily::where(function ($query) use ($search) {
            // Apply search filter if provided
            if ($search) {
                $query->search($search);
            }
        });

        $query->with('familyMembers')->latest();

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
    ): ?HeadOfFamily {
        $query = HeadOfFamily::where('id', $id);

        return $query->first();
    }

    public function create(
        array $data
    ): HeadOfFamily {
        DB::beginTransaction();

        try {
            $userRepository = new UserRepository();

            $user = $userRepository->create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => $data['password'],
            ])->assignRole('head-of-family');


            $headOfFamily = new HeadOfFamily();
            $headOfFamily->user_id = $user->id;
            $headOfFamily->profile_picture = $data['profile_picture']->store('assets/head-of-families', 'public');
            $headOfFamily->identify_number = $data['identify_number'];
            $headOfFamily->gender = $data['gender'];
            $headOfFamily->birth_date = $data['birth_date'];
            $headOfFamily->phone_number = $data['phone_number'];
            $headOfFamily->occupation = $data['occupation'];
            $headOfFamily->marital_status = $data['marital_status'];

            $headOfFamily->save();

            DB::commit();
            return $headOfFamily;
        } catch (\Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage());
        }
    }

    public function update(
        string $id,
        array $data
    ): ?HeadOfFamily {
        DB::beginTransaction();

        try {
            $headOfFamily = HeadOfFamily::find($id);

            if (isset($data['profile_picture'])) {
                $oldProfilePicture = $headOfFamily->profile_picture;

                $headOfFamily->profile_picture = $data['profile_picture']->store('assets/head-of-families', 'public');

                if ($oldProfilePicture && Storage::disk('public')->exists($oldProfilePicture)) {
                    Storage::disk('public')->delete($oldProfilePicture);
                }
            }

            $headOfFamily->user_id = $data['user_id'] ?? $headOfFamily->user_id;
            $headOfFamily->identify_number = $data['identify_number'] ?? $headOfFamily->identify_number;
            $headOfFamily->gender = $data['gender'] ?? $headOfFamily->gender;
            $headOfFamily->birth_date = $data['birth_date'] ?? $headOfFamily->birth_date;
            $headOfFamily->phone_number = $data['phone_number'] ?? $headOfFamily->phone_number;
            $headOfFamily->occupation = $data['occupation'] ?? $headOfFamily->occupation;
            $headOfFamily->marital_status = $data['marital_status'] ?? $headOfFamily->marital_status;

            $headOfFamily->save();

            DB::commit();

            return $headOfFamily;
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
            $headOfFamily = HeadOfFamily::find($id);

            if ($headOfFamily->profile_picture && Storage::disk('public')->exists($headOfFamily->profile_picture)) {
                Storage::disk('public')->delete($headOfFamily->profile_picture);
            }

            $headOfFamily->delete();

            DB::commit();

            return $headOfFamily;
        } catch (\Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage());
        }
    }
}
