<?php

namespace Database\Seeders;

use App\Models\FamilyMember;
use App\Models\HeadOfFamily;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class HeadOfFamilySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create 15 realistic family structures
        for ($i = 0; $i < 15; $i++) {
            $this->createRealisticFamily();
        }
    }

    private function createRealisticFamily(): void
    {
        // 70% married, 30% single
        $isMarried = fake()->boolean(70);

        // If married, head must be male (husband). If single, can be male or female
        $headGender = $isMarried ? 'male' : fake()->randomElement(['male', 'male', 'female']);

        // Create user for head of family
        $headUser = User::factory()->create([
            'name' => fake()->name($headGender),
        ]);

        // Assign head-of-family role
        $headUser->assignRole('head-of-family');

        // Create head of family
        $birthDate = fake()->dateTimeBetween('-60 years', '-25 years')->format('Y-m-d');
        $photos = [
            'assets/data-seeder/photos/kk-photo-1.png',
            'assets/data-seeder/photos/kk-photo-2.png',
            'assets/data-seeder/photos/kk-photo-3.png',
            'assets/data-seeder/photos/kk-photo-4.png',
            'assets/data-seeder/photos/kk-photo-5.png',
            'assets/data-seeder/photos/photo-1.png',
            'assets/data-seeder/photos/photo-2.png',
        ];
        $headOfFamily = HeadOfFamily::create([
            'user_id' => $headUser->id,
            'profile_picture' => fake()->randomElement($photos),
            'identify_number' => fake()->unique()->numerify('##########'),
            'gender' => $headGender,
            'birth_date' => $birthDate,
            'phone_number' => fake()->phoneNumber(),
            'occupation' => fake()->randomElement([
                'Pegawai Negeri Sipil',
                'Wiraswasta',
                'Karyawan Swasta',
                'Petani',
                'Pedagang',
                'Guru',
                'Dokter',
                'Buruh',
                'Sopir',
                'Pengusaha',
                'TNI/Polri',
                'Karyawan BUMN'
            ]),
            'marital_status' => $isMarried ? 'married' : 'single',
        ]);

        if ($isMarried) {
            $this->createMarriedFamilyMembers($headOfFamily, $headGender);
        } else {
            // Single parent with possible children (40% chance)
            if (fake()->boolean(40)) {
                $this->createChildren($headOfFamily, fake()->numberBetween(1, 2));
            }
        }
    }

    private function createMarriedFamilyMembers(HeadOfFamily $headOfFamily, string $headGender): void
    {
        // Create spouse (wife, since head is always male when married)
        $spouseGender = 'female';
        $spouseRelation = 'wife';

        $spouseUser = User::factory()->create([
            'name' => fake()->name($spouseGender),
        ]);

        $spouseBirthDate = fake()->dateTimeBetween('-55 years', '-23 years')->format('Y-m-d');
        $photos = [
            'assets/data-seeder/photos/kk-photo-1.png',
            'assets/data-seeder/photos/kk-photo-2.png',
            'assets/data-seeder/photos/kk-photo-3.png',
            'assets/data-seeder/photos/kk-photo-4.png',
            'assets/data-seeder/photos/kk-photo-5.png',
            'assets/data-seeder/photos/photo-1.png',
            'assets/data-seeder/photos/photo-2.png',
        ];
        FamilyMember::create([
            'head_of_family_id' => $headOfFamily->id,
            'user_id' => $spouseUser->id,
            'profile_picture' => fake()->randomElement($photos),
            'identify_number' => fake()->unique()->numerify('##########'),
            'gender' => $spouseGender,
            'birth_date' => $spouseBirthDate,
            'phone_number' => fake()->phoneNumber(),
            'occupation' => fake()->randomElement([
                'Ibu Rumah Tangga',
                'Pegawai Negeri Sipil',
                'Wiraswasta',
                'Karyawan Swasta',
                'Guru',
                'Perawat',
                'Pedagang',
                'Karyawan BUMN'
            ]),
            'marital_status' => 'married',
            'relation' => $spouseRelation,
        ]);

        // Create children (70% have children, 0-4 children)
        if (fake()->boolean(70)) {
            $numberOfChildren = fake()->numberBetween(1, 4);
            $this->createChildren($headOfFamily, $numberOfChildren);
        }
    }

    private function createChildren(HeadOfFamily $headOfFamily, int $count): void
    {
        for ($i = 0; $i < $count; $i++) {
            $childGender = fake()->randomElement(['male', 'female']);
            $childUser = User::factory()->create([
                'name' => fake()->name($childGender),
            ]);

            // Children age range: 0-25 years
            $childBirthDateTime = fake()->dateTimeBetween('-25 years', 'now');
            $childBirthDate = $childBirthDateTime->format('Y-m-d');
            $age = $this->calculateAge($childBirthDate);

            $photos = [
                'assets/data-seeder/photos/kk-photo-1.png',
                'assets/data-seeder/photos/kk-photo-2.png',
                'assets/data-seeder/photos/kk-photo-3.png',
                'assets/data-seeder/photos/kk-photo-4.png',
                'assets/data-seeder/photos/kk-photo-5.png',
                'assets/data-seeder/photos/photo-1.png',
                'assets/data-seeder/photos/photo-2.png',
            ];
            FamilyMember::create([
                'head_of_family_id' => $headOfFamily->id,
                'user_id' => $childUser->id,
                'profile_picture' => fake()->randomElement($photos),
                'identify_number' => fake()->unique()->numerify('##########'),
                'gender' => $childGender,
                'birth_date' => $childBirthDate,
                'phone_number' => $age >= 12 ? fake()->phoneNumber() : null,
                'occupation' => $this->getChildOccupation($age),
                'marital_status' => 'single',
                'relation' => 'child',
            ]);
        }
    }

    private function calculateAge(string $birthDate): int
    {
        return \Carbon\Carbon::parse($birthDate)->age;
    }

    private function getChildOccupation(int $age): ?string
    {
        if ($age >= 0 && $age < 6) {
            return 'Belum Bekerja';
        } elseif ($age >= 6 && $age < 18) {
            return 'Pelajar';
        } elseif ($age >= 18 && $age < 25) {
            return 'Mahasiswa';
        }

        return fake()->randomElement([
            'Pelajar',
            'Mahasiswa',
            'Karyawan Swasta',
            'Wiraswasta',
            'Belum Bekerja'
        ]);
    }
}
