<?php

namespace App\Interfaces;

use App\Models\FamilyMember;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection as SupportCollection;

interface FamilyMemberRepositoryInterface
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
    ): ?FamilyMember;

    public function getByHeadOfFamilyId(
        string $headOfFamilyId
    ): SupportCollection;

    public function create(
        array $data
    ): FamilyMember;

    public function update(
        string $id,
        array $data
    ): ?FamilyMember;

    public function delete(
        string $id
    ): bool;
}
