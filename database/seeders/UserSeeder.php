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
        ])->assignRole('admin');

        User::query()->create([
            'name' => 'Kepala Kelurahan',
            'email' => 'headoffamily@example.com',
            'password' => bcrypt('password'),
        ])->assignRole('head-of-family');

        UserFactory::new()->count(15)->create();
    }
}
