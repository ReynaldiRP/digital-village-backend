<?php

namespace App\Repositories;

use App\Interfaces\DevelopmentApplicantRepositoryInterface;
use App\Models\DevelopmentApplicant;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class DevelopmentApplicantRepository implements DevelopmentApplicantRepositoryInterface
{
    public function getAll(
        ?string $search,
        ?int $limit,
        bool $execute
    ): Collection {
        try {
            $query = DevelopmentApplicant::where(function ($query) use ($search) {
                if ($search) {
                    $query->search($search);
                }
            });

            if ($limit) {
                $query->take($limit);
            }

            if ($execute) {
                return $query->get();
            }

            return $query;
        } catch (\Exception $e) {
            return throw new \Exception($e->getMessage());
        }
    }

    public function getAllPaginated(
        ?string $search,
        int $rowPerPage
    ): LengthAwarePaginator {
        try {
            $query = $this->getAll(
                $search,
                $rowPerPage,
                false
            );

            return $query->paginate($rowPerPage);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function getById(
        string $id
    ): ?DevelopmentApplicant {
        try {
            $query = DevelopmentApplicant::where('id', $id);
            return $query->first();
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function create(
        array $data
    ): DevelopmentApplicant {
        DB::beginTransaction();
        try {
            $developmentApplicant = new DevelopmentApplicant();
            $developmentApplicant->development_id = $data['development_id'];
            $developmentApplicant->user_id = $data['user_id'];
            $developmentApplicant->status = $data['status'];
            $developmentApplicant->save();
            DB::commit();

            return $developmentApplicant;
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception($e->getMessage());
        }
    }

    public function update(
        string $id,
        array $data
    ): ?DevelopmentApplicant {
        DB::beginTransaction();
        try {
            $developmentApplicant = DevelopmentApplicant::find($id);

            $developmentApplicant->development_id = $data['development_id'] ?? $developmentApplicant->development_id;
            $developmentApplicant->user_id = $data['user_id'] ?? $developmentApplicant->user_id;
            $developmentApplicant->status = $data['status'] ?? $developmentApplicant->status;
            $developmentApplicant->save();
            DB::commit();

            return $developmentApplicant;
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception($e->getMessage());
        }
    }

    public function delete(
        string $id
    ): bool {
        DB::beginTransaction();
        try {
            $developmentApplicant = DevelopmentApplicant::find($id);
            $developmentApplicant->delete();
            DB::commit();

            return $developmentApplicant;
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception($e->getMessage());
        }
    }
}
