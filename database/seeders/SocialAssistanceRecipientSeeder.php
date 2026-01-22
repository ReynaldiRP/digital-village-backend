<?php

namespace Database\Seeders;

use App\Models\HeadOfFamily;
use App\Models\SocialAssistance;
use App\Models\SocialAssistanceRecipient;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SocialAssistanceRecipientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $headOfFamilies = HeadOfFamily::all();
        $socialAssistances = SocialAssistance::where('is_available', true)->get();

        // Each family has 40% chance of receiving 1-2 assistance programs
        foreach ($headOfFamilies as $family) {
            if (fake()->boolean(40)) {
                $programsCount = fake()->numberBetween(1, 2);
                $selectedPrograms = $socialAssistances->random(min($programsCount, $socialAssistances->count()));

                foreach ($selectedPrograms as $program) {
                    // Avoid duplicates
                    if (SocialAssistanceRecipient::where('head_of_family_id', $family->id)
                        ->where('social_assistance_id', $program->id)
                        ->exists()
                    ) {
                        continue;
                    }

                    SocialAssistanceRecipient::create([
                        'social_assistance_id' => $program->id,
                        'head_of_family_id' => $family->id,
                        'amount' => $program->amount > 0 ? $program->amount : fake()->numberBetween(100000, 500000),
                        'reason' => $this->getRealisticReason($program->category),
                        'bank' => fake()->randomElement(['BRI', 'BNI', 'BCA', 'Mandiri']),
                        'account_number' => fake()->numerify('##########'),
                        'proof' => fake()->randomElement([
                            'assets/data-seeder/thumbnails/kk-bukti-menerima-bansos.png',
                            'assets/data-seeder/thumbnails/kk-bca.png',
                            'assets/data-seeder/thumbnails/kk-bni.png',
                            'assets/data-seeder/thumbnails/kk-mandiri.png',
                        ]),
                        'status' => fake()->randomElement([
                            'diterima',
                            'diterima',
                            'diterima',
                            'menunggu',
                            'menunggu',
                            'ditolak'
                        ])
                    ]);
                }
            }
        }
    }

    private function getRealisticReason(string $category): string
    {
        $reasons = [
            'cash' => [
                'Penghasilan keluarga di bawah UMR',
                'Kepala keluarga kehilangan pekerjaan',
                'Keluarga termasuk kategori pra-sejahtera',
                'Terdampak bencana alam',
            ],
            'staple' => [
                'Kesulitan memenuhi kebutuhan pangan sehari-hari',
                'Keluarga besar dengan penghasilan terbatas',
                'Terdaftar sebagai keluarga kurang mampu',
            ],
            'health' => [
                'Tidak mampu membayar biaya kesehatan',
                'Memiliki anggota keluarga dengan penyakit kronis',
                'Pendapatan tidak mencukupi untuk iuran BPJS',
            ],
            'subsidized' => [
                'Penghasilan keluarga terbatas',
                'Kesulitan membayar biaya utilitas',
                'Termasuk kategori masyarakat berpenghasilan rendah',
            ],
        ];

        $categoryReasons = $reasons[$category] ?? ['Membutuhkan bantuan sosial'];
        return fake()->randomElement($categoryReasons);
    }
}
