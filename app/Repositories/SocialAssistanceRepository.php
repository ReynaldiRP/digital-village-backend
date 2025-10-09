<?php

namespace App\Repositories;

use App\Interfaces\SocialAssistanceRepositoryInterface;
use App\Models\SocialAssistance;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class SocialAssistanceRepository implements SocialAssistanceRepositoryInterface
{
    public function getAll(
        ?string $search,
        ?int $limit,
        bool $execute
    ): Collection {
        $query = SocialAssistance::where(function ($query) use ($search) {
            if ($search) {
                $query->search($search);
            }
        });

        $query->latest();

        // Apply limit if provided

        if ($limit) {
            $query->limit($limit);
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
    ): ?SocialAssistance {
        $query = SocialAssistance::where('id', $id);

        return $query->first();
    }

    public function create(
        array $data
    ): SocialAssistance {
        DB::beginTransaction();

        try {
            $socialAssistance = new SocialAssistance();

            $socialAssistance->thumbnail = $data['thumbnail']->store('assets/social-assistances', 'public');
            $socialAssistance->name = $data['name'];
            $socialAssistance->category = $data['category'];
            $socialAssistance->amount = $data['amount'];
            $socialAssistance->provider = $data['provider'];
            $socialAssistance->description = $data['description'];
            $socialAssistance->is_available = $data['is_available'];
            $socialAssistance->save();

            DB::commit();

            return $socialAssistance;
        } catch (\Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage());
        }
    }

    public function update(
        string $id,
        array $data
    ): ?SocialAssistance {
        DB::beginTransaction();

        try {
            $socialAssistance = SocialAssistance::find($id);

            if (isset($data['thumbnail'])) {
                $oldThumbnail = $socialAssistance->thumbnail;

                $socialAssistance->thumbnail = $data['thumbnail']->store('assets/social-assistances', 'public');

                if ($oldThumbnail && Storage::disk('public')->exists($oldThumbnail)) {
                    Storage::disk('public')->delete($oldThumbnail);
                }
            }

            $socialAssistance->name = $data['name'] ?? $socialAssistance->name;
            $socialAssistance->category = $data['category'] ?? $socialAssistance->category;
            $socialAssistance->amount = $data['amount'] ?? $socialAssistance->amount;
            $socialAssistance->provider = $data['provider'] ?? $socialAssistance->provider;
            $socialAssistance->description = $data['description'] ?? $socialAssistance->description;
            $socialAssistance->is_available = $data['is_available'] ?? $socialAssistance->is_available;
            $socialAssistance->save();

            DB::commit();

            return $socialAssistance;
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
            $socialAssistance = SocialAssistance::find($id);

            if ($socialAssistance->thumbnail && Storage::disk('public')->exists($socialAssistance->thumbnail)) {
                Storage::disk('public')->delete($socialAssistance->thumbnail);
            }

            $socialAssistance->delete();

            DB::commit();

            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage());
        }
    }
}
