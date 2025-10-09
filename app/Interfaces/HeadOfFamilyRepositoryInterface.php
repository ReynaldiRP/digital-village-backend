<?php

namespace App\Interfaces;

use App\Models\HeadOfFamily;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface HeadOfFamilyRepositoryInterface
{
    public function getAll(
        ?string $search,
        ?int $limit,
        bool $execute
    ): Collection;

    public function getAllPaginated(
        ?string $search,
        ?int $rowPerPage,
    ): LengthAwarePaginator;

    public function getById(
        string $id
    ): ?HeadOfFamily;

    public function create(
        array $data
    ): HeadOfFamily;

    public function update(
        string $id,
        array $data
    ): ?HeadOfFamily;

    public function delete(
        string $id
    ): bool;
}
