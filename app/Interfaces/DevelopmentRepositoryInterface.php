<?php

namespace App\Interfaces;

use App\Models\Development;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface DevelopmentRepositoryInterface
{
    public function getAll(
        ?string $search,
        ?int $limit,
        bool $execute
    ): Collection;

    public function getAllPaginated(
        ?string $search,
        int $rowPerPage
    ): LengthAwarePaginator;

    public function create(
        array $data
    ): Development;

    public function getById(
        string $id
    ): ?Development;

    public function update(
        string $id,
        array $data
    ): ?Development;

    public function delete(
        string $id
    ): bool;
}
