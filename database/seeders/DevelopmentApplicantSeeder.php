<?php

namespace Database\Seeders;

use App\Models\Development;
use App\Models\DevelopmentApplicant;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DevelopmentApplicantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $developments = Development::all();
        $users = User::whereHas('headOfFamily')->get();

        // 30-40% of users apply for development projects
        foreach ($users as $user) {
            if (fake()->boolean(35)) {
                // Each user can apply to 1-2 projects
                $projectsCount = fake()->numberBetween(1, min(2, $developments->count()));
                $selectedProjects = $developments->random($projectsCount);

                foreach ($selectedProjects as $project) {
                    // Avoid duplicates
                    if (DevelopmentApplicant::where('user_id', $user->id)
                        ->where('development_id', $project->id)
                        ->exists()
                    ) {
                        continue;
                    }

                    // Application date must be before or equal to project start date
                    $projectStartDate = \Carbon\Carbon::parse($project->start_date);
                    $applicationDate = fake()->dateTimeBetween(
                        $projectStartDate->copy()->subMonths(3),
                        $projectStartDate
                    );

                    DevelopmentApplicant::create([
                        'development_id' => $project->id,
                        'user_id' => $user->id,
                        'status' => $this->getApplicationStatus($project->status),
                        'created_at' => $applicationDate,
                        'updated_at' => $applicationDate,
                    ]);
                }
            }
        }
    }

    private function getApplicationStatus(string $projectStatus): string
    {
        // Completed projects: mostly approved applications
        if ($projectStatus === 'selesai') {
            return fake()->randomElement([
                'diterima',
                'diterima',
                'diterima',
                'diterima',
                'ditolak'
            ]);
        }

        // Ongoing projects: mix of statuses
        return fake()->randomElement([
            'diterima',
            'diterima',
            'diterima',
            'menunggu',
            'menunggu',
            'ditolak'
        ]);
    }
}
