<?php

namespace App\Interfaces;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface UserRepositoryInterface
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

    public function create(
        array $data
    ): User;

    public function getById(
        string $id
    ): ?User;

    public function update(
        string $id,
        array $data
    ): ?User;

    public function delete(
        string $id
    ): bool;
}
