<?php

namespace Database\Seeders;

use App\Models\FamilyMember;
use Database\Factories\FamilyMemberFactory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FamilyMemberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Family members are now created in HeadOfFamilySeeder
        // This seeder is kept for backward compatibility but does nothing
        // Remove this seeder call from DatabaseSeeder if not needed
    }
}
