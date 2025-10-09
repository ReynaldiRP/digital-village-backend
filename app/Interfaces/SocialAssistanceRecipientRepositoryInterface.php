<?php

namespace App\Interfaces;

use App\Models\SocialAssistanceRecipient;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface SocialAssistanceRecipientRepositoryInterface
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
    ): ?SocialAssistanceRecipient;

    public function create(
        array $data
    ): SocialAssistanceRecipient;

    public function update(
        string $id,
        array $data
    ): ?SocialAssistanceRecipient;

    public function delete(
        string $id
    ): bool;
}
