<?php

namespace App\Interfaces;

use App\Models\EventParticipant;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface EventParticipantRepositoryInterface
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
    ): EventParticipant;

    public function getById(
        string $id
    ): ?EventParticipant;

    public function update(
        string $id,
        array $data
    ): ?EventParticipant;

    public function delete(
        string $id
    ): bool;
}
