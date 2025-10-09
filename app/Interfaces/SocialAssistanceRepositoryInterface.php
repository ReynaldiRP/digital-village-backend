<?php

namespace App\Interfaces;

use App\Models\SocialAssistance;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface SocialAssistanceRepositoryInterface
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
    ): ?SocialAssistance;

    public function create(
        array $data
    ): SocialAssistance;

    public function update(
        string $id,
        array $data
    ): ?SocialAssistance;

    public function delete(
        string $id
    ): bool;
}
