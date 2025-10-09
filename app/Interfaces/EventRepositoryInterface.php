<?php

namespace App\Interfaces;

use App\Models\Event;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface EventRepositoryInterface
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
    ): Event;

    public function getById(
        string $id
    ): ?Event;

    public function update(
        string $id,
        array $data
    ): ?Event;

    public function delete(
        string $id
    ): bool;
}
