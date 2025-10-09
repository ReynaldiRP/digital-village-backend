<?php

namespace App\Repositories;

use App\Interfaces\DevelopmentRepositoryInterface;
use App\Models\Development;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class DevelopmentRepository implements DevelopmentRepositoryInterface
{
    public function getAll(
        ?string $search,
        ?int $limit,
        bool $execute
    ): Collection {
        try {
            $query = Development::where(function ($query) use ($search) {
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
    ): ?Development {
        try {
            $query = Development::where('id', $id);
            return $query->first();
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function create(
        array $data
    ): Development {
        DB::beginTransaction();
        try {
            $development = new Development();
            $development->thumbnail = $data['thumbnail']->store('assets/developments', 'public');
            $development->name = $data['name'];
            $development->description = $data['description'];
            $development->person_in_charge = $data['person_in_charge'];
            $development->start_date = $data['start_date'];
            $development->end_date = $data['end_date'];
            $development->amount = $data['amount'];
            $development->status = $data['status'];
            $development->save();
            DB::commit();

            return $development;
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception($e->getMessage());
        }
    }

    public function update(
        string $id,
        array $data
    ): ?Development {
        DB::beginTransaction();

        try {
            $development = Development::find($id);

            if (isset($data['thumbnail'])) {
                $oldThumbnail = $development->thumbnail;
                $development->thumbnail = $data['thumbnail']->store('assets/developments', 'public');
                if ($oldThumbnail && Storage::disk('public')->exists($oldThumbnail)) {
                    Storage::disk('public')->delete($oldThumbnail);
                }
            }
            $development->name = $data['name'] ?? $development->name;
            $development->description = $data['description'] ?? $development->description;
            $development->person_in_charge = $data['person_in_charge'] ?? $development->person_in_charge;
            $development->start_date = $data['start_date'] ?? $development->start_date;
            $development->end_date = $data['end_date'] ?? $development->end_date;
            $development->amount = $data['amount'] ?? $development->amount;
            $development->status = $data['status'] ?? $development->status;
            $development->save();
            DB::commit();

            return $development;
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
            $development = Development::find($id);
            if ($development->thumbnail && Storage::disk('public')->exists($development->thumbnail)) {
                Storage::disk('public')->delete($development->thumbnail);
            }
            $development->delete();
            DB::commit();

            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception($e->getMessage());
        }
    }
}
