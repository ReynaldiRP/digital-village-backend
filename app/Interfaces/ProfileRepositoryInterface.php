<?php

namespace App\Interfaces;

interface ProfileRepositoryInterface
{
    public function get(): ?object;

    public function create(
        array $data
    ): object;

    public function update(
        array $data
    ): ?object;
}
