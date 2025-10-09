<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    private $permission = [
        'dashboard' => [
            'menu'
        ],
        'head-of-family' => [
            'menu',
            'create',
            'read',
            'update',
            'delete',
            'export',
        ],
        'family-member' => [
            'menu',
            'create',
            'read',
            'update',
            'delete',
            'export',
        ],
        'social-assistance' => [
            'menu',
            'create',
            'read',
            'update',
            'delete',
            'export',
        ],
        'social-assistance-recipient' => [
            'menu',
            'create',
            'read',
            'update',
            'delete',
            'export',
        ],
        'event' => [
            'menu',
            'create',
            'read',
            'update',
            'delete',
            'export',
        ],
        'event-participant' => [
            'menu',
            'create',
            'read',
            'update',
            'delete',
            'export',
        ],
        'development' => [
            'menu',
            'create',
            'read',
            'update',
            'delete',
            'export',
        ],
        'development-applicant' => [
            'menu',
            'create',
            'read',
            'update',
            'delete',
            'export',
        ],
        'profile' => [
            'menu',
            'create',
            'update',
        ],
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach ($this->permission as $key => $value) {
            foreach ($value as $item) {
                \Spatie\Permission\Models\Permission::firstOrCreate([
                    'name' => $key . '-' . $item,
                    'guard_name' => 'sanctum',
                ]);
            }
        }
    }
}
