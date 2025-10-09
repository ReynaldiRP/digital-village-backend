<?php

namespace App\Repositories;

use App\Interfaces\SocialAssistanceRecipientRepositoryInterface;
use App\Models\SocialAssistance;
use App\Models\SocialAssistanceRecipient;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class SocialAssistanceRecipientRepository implements SocialAssistanceRecipientRepositoryInterface
{
    public function getAll(
        ?string $search,
        ?int $limit,
        bool $execute
    ): Collection {
        $query = SocialAssistanceRecipient::where(function ($query) use ($search) {
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
    ): ?SocialAssistanceRecipient {
        $query = SocialAssistanceRecipient::where('id', $id);

        return $query->first();
    }

    public function create(
        array $data
    ): SocialAssistanceRecipient {
        DB::beginTransaction();

        try {
            $socialAssistanceRecipient = new SocialAssistanceRecipient();

            $socialAssistanceRecipient->social_assistance_id = $data['social_assistance_id'];
            $socialAssistanceRecipient->head_of_family_id = $data['head_of_family_id'];
            $socialAssistanceRecipient->amount = $data['amount'];
            $socialAssistanceRecipient->reason = $data['reason'];
            $socialAssistanceRecipient->bank = $data['bank'];
            $socialAssistanceRecipient->account_number = $data['account_number'];
            $socialAssistanceRecipient->proof = $data['proof']->store('assets/social-assistance-recipients', 'public');

            $socialAssistanceRecipient->status = $data['status'];

            $socialAssistanceRecipient->save();

            DB::commit();

            return $socialAssistanceRecipient;
        } catch (\Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage());
        }
    }

    public function update(
        string $id,
        array $data
    ): ?SocialAssistanceRecipient {
        DB::beginTransaction();

        try {
            $socialAssistanceRecipient = SocialAssistanceRecipient::find($id);

            if (isset($data['proof'])) {
                $oldProof = $socialAssistanceRecipient->proof;
                $socialAssistanceRecipient->proof = $data['proof']->store('assets/social-assistance-recipients', 'public');
                if ($oldProof && Storage::disk('public')->exists($oldProof)) {
                    Storage::disk('public')->delete($oldProof);
                }
            }

            $socialAssistanceRecipient->social_assistance_id = $data['social_assistance_id'] ?? $socialAssistanceRecipient->social_assistance_id;
            $socialAssistanceRecipient->head_of_family_id = $data['head_of_family_id'] ?? $socialAssistanceRecipient->head_of_family_id;
            $socialAssistanceRecipient->amount = $data['amount'] ?? $socialAssistanceRecipient->amount;
            $socialAssistanceRecipient->reason = $data['reason'] ?? $socialAssistanceRecipient->reason;
            $socialAssistanceRecipient->bank = $data['bank'] ?? $socialAssistanceRecipient->bank;
            $socialAssistanceRecipient->account_number = $data['account_number'] ?? $socialAssistanceRecipient->account_number;
            $socialAssistanceRecipient->status = $data['status'] ?? $socialAssistanceRecipient->status;
            $socialAssistanceRecipient->save();

            DB::commit();

            return $socialAssistanceRecipient;
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
            $socialAssistanceRecipient = SocialAssistanceRecipient::find($id);

            if ($socialAssistanceRecipient->proof && Storage::disk('public')->exists($socialAssistanceRecipient->proof)) {
                Storage::disk('public')->delete($socialAssistanceRecipient->proof);
            }

            $socialAssistanceRecipient->delete();

            DB::commit();

            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage());
        }
    }
}
