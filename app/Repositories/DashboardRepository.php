<?php

namespace App\Repositories;

use App\Helpers\UserAgeHelper;
use App\Interfaces\DashboardRepositoryInterface;
use App\Models\Development;
use App\Models\DevelopmentApplicant;
use App\Models\Event;
use App\Models\FamilyMember;
use App\Models\HeadOfFamily;
use App\Models\SocialAssistance;
use App\Models\SocialAssistanceRecipient;
use App\Models\User;
use DateTime;

class DashboardRepository implements DashboardRepositoryInterface
{
    public function getDashboardData(): array
    {
        return [
            'residents' => HeadOfFamily::count() + FamilyMember::count(),
            'head_of_families' => HeadOfFamily::count(),
            'social_assistances' => SocialAssistance::count(),
            'events' => Event::count(),
            'developments' => Development::count(),
        ];
    }

    public function getRecentSocialAssistances(
        int $limit = 4
    ): array {
        return SocialAssistanceRecipient::with([
            'headOfFamily',
            'headOfFamily.user',
            'socialAssistance'
        ])->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get()
            ->map(function ($recipient) {
                return [
                    'id' => $recipient->id,
                    'thumbnail' => $recipient->socialAssistance->thumbnail ? asset('storage/' . $recipient->socialAssistance->thumbnail) : null,
                    'recipient_name' => $recipient->headOfFamily->user->name,
                    'social_assistance' => $recipient->socialAssistance->name,
                    'amount' => $recipient->amount,
                    'status' => $recipient->status,
                    'received_at' => $recipient->created_at->format('d F Y H:i'),
                ];
            })
            ->toArray();
    }

    public function getRecentDevelopmentApplicants(
        int $limit = 4
    ): array {
        return DevelopmentApplicant::with(['user', 'user.headOfFamily', 'development'])
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get()
            ->map(function ($applicant) {
                return [
                    'id' => $applicant->id,
                    'applicant_name' => $applicant->user->name ?? null,
                    'project_name' => $applicant->development->name ?? null,
                    'amount_requested' => $applicant->development->amount ?? null,
                    'status' => $applicant->status,
                    'applied_at' => $applicant->created_at->format('d F Y H:i'),
                    'thumbnail' => $applicant->development->thumbnail ? asset('storage/' . $applicant->development->thumbnail) : null,
                    'applicant_photo' => $applicant->user->headOfFamily->profile_picture ? asset('storage/' . $applicant->user->headOfFamily->profile_picture) : null,
                ];
            })
            ->toArray();
    }

    public function getAgeDistribution(): array
    {
        $userBirthDates = User::query()->pluck('birth_date');
        $ageDistribution = [
            'Bayi' => 0,
            'Balita' => 0,
            'Anak-anak' => 0,
            'Remaja' => 0,
            'Dewasa' => 0,
            'Lansia Awal' => 0,
            'Lansia Akhir' => 0,
            'Manula' => 0,
        ];

        foreach ($userBirthDates as $birthDate) {
            $age = UserAgeHelper::calculateAge(DateTime::createFromFormat('Y-m-d H:i:s', $birthDate));
            $category = UserAgeHelper::classifyAge($age);

            if (isset($ageDistribution[$category])) {
                $ageDistribution[$category]++;
            }
        }

        return $ageDistribution;
    }
}
