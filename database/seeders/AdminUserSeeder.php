<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin; // Your Admin model
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $adminUser = Admin::firstOrCreate(
            ['email' => 'test@example.com'], // Unique identifier to find existing user
            [
                'name' => 'San Grilla',
                'password' => Hash::make('Password'),
            ]
        );

        // Find the 'Admin' role (ensure it's created by RolesAndPermissionsSeeder first)
        // Specify guard_name for Admin model
        $adminRole = Role::where('name', 'Admin')->where('guard_name', 'admins')->first(); 

        if ($adminRole) {
            $adminUser->assignRole($adminRole);
        } else {
            // Optionally, handle the case where the role doesn't exist,
            // though RolesAndPermissionsSeeder should have created it.
            $this->command->error('Admin role (guard: admins) not found. Please run RolesAndPermissionsSeeder first and ensure it creates the role for the "admins" guard.');
        }
    }
}
