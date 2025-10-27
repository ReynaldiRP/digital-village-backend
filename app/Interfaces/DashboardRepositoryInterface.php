<?php

namespace App\Interfaces;

interface DashboardRepositoryInterface
{
    public function getDashboardData(): array;
    public function getRecentSocialAssistances(
        int $limit = 4
    ): array;
    public function getRecentDevelopmentApplicants(
        int $limit = 4
    ): array;
}
