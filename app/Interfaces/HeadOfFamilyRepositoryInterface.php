<?php

namespace App\Interfaces;

use App\Models\HeadOfFamily;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface HeadOfFamilyRepositoryInterface
{
    public function getAll(
        ?string $search,
        ?array $filters,
        ?int $limit,
        bool $execute
    ): Collection | Builder;

    public function getAllPaginated(
        ?string $search,
        ?array $filters,
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

    public function applyFilters(
        array $filters
    ): Builder;
}
