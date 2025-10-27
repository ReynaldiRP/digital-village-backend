<?php

namespace Database\Seeders;

use App\Models\HeadOfFamily;
use Database\Factories\SocialAssistanceFactory;
use Database\Factories\SocialAssistanceRecipientFactory;
use Illuminate\Database\Seeder;

class SocialAssistanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SocialAssistanceFactory::new()->count(25)->create();
    }
}
