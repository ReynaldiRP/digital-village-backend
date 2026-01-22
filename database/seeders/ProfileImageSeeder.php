<?php

namespace Database\Seeders;

use App\Models\Profile;
use App\Models\ProfileImage;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProfileImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $profile = Profile::first();

        if (!$profile) {
            $this->command->warn('No profile found. Please run ProfileSeeder first.');
            return;
        }

        $galleryImages = [
            'assets/data-seeder/thumbnails/desa-gallery-2.png',
            'assets/data-seeder/thumbnails/desa-gallery-3.png',
            'assets/data-seeder/thumbnails/desa-gallery-4.png',
            'assets/data-seeder/thumbnails/desa-gallery-5.png',
            'assets/data-seeder/thumbnails/many-people-signin.png',
        ];

        foreach ($galleryImages as $image) {
            ProfileImage::create([
                'profile_id' => $profile->id,
                'image' => $image,
            ]);
        }
    }
}
