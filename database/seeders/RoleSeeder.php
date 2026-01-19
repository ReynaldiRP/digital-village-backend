<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::query()->create([
            'name' => 'admin',
            'guard_name' => 'sanctum',
        ])->givePermissionTo(\Spatie\Permission\Models\Permission::all());

        Role::query()->create([
            'name' => 'head-of-family',
            'guard_name' => 'sanctum',
        ])->givePermissionTo([
            'dashboard-menu',
            
            'family-member-menu',
            'family-member-read',
            'family-member-create',
            'family-member-update',
            'family-member-delete',
            'family-member-export',

            'social-assistance-menu',
            'social-assistance-read',

            'social-assistance-recipient-menu',
            'social-assistance-recipient-read',
            'social-assistance-recipient-create',
            'social-assistance-recipient-update',
            'social-assistance-recipient-delete',
            'social-assistance-recipient-export',

            'event-menu',
            'event-read',

            'event-participant-menu',
            'event-participant-read',
            'event-participant-create',
            'event-participant-update',
            'event-participant-delete',
            'event-participant-export',

            'development-menu',
            'development-read',

            'profile-menu',

        ]);
    }
}
