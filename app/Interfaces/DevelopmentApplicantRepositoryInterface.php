<?php

namespace App\Interfaces;

use App\Models\DevelopmentApplicant;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface DevelopmentApplicantRepositoryInterface
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
    ): DevelopmentApplicant;

    public function getById(
        string $id
    ): ?DevelopmentApplicant;

    public function update(
        string $id,
        array $data
    ): ?DevelopmentApplicant;

    public function delete(
        string $id
    ): bool;
}
