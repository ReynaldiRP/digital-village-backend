<?php

namespace Database\Seeders;

use App\Models\User;
use Database\Factories\UserFactory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::query()->create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'birth_date' => '1990-01-01 00:00:00',
        ])->assignRole('admin');

        User::query()->create([
            'name' => 'Kepala Kelurahan',
            'email' => 'headoffamily@example.com',
            'password' => bcrypt('password'),
            'birth_date' => '1985-05-15 00:00:00',
        ])->assignRole('head-of-family');

        UserFactory::new()->count(15)->create();
    }
}
