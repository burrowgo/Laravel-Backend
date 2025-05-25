<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role; // Import Role

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Permissions for 'web' guard (for User model)
        $webPermViewBooks = Permission::firstOrCreate(['name' => 'view books', 'guard_name' => 'web']);
        $webPermCreateBooks = Permission::firstOrCreate(['name' => 'create books', 'guard_name' => 'web']);
        $webPermEditBooks = Permission::firstOrCreate(['name' => 'edit books', 'guard_name' => 'web']);
        $webPermDeleteBooks = Permission::firstOrCreate(['name' => 'delete books', 'guard_name' => 'web']);
        // $webPermManageUsers = Permission::firstOrCreate(['name' => 'manage users', 'guard_name' => 'web']); // If User manages other Users
        // $webPermManageRoles = Permission::firstOrCreate(['name' => 'manage roles', 'guard_name' => 'web']); // If User manages its own roles
        // $webPermAssignRoles = Permission::firstOrCreate(['name' => 'assign roles', 'guard_name' => 'web']); // If User assigns roles

        // Viewer Role (web guard)
        $viewerRole = Role::firstOrCreate(['name' => 'Viewer', 'guard_name' => 'web']);
        $viewerRole->givePermissionTo([$webPermViewBooks]);

        // Editor Role (web guard)
        $editorRole = Role::firstOrCreate(['name' => 'Editor', 'guard_name' => 'web']);
        $editorRole->givePermissionTo([
            $webPermViewBooks,
            $webPermCreateBooks,
            $webPermEditBooks,
            $webPermDeleteBooks,
        ]);

        // Permissions for 'admins' guard (for Admin model)
        // These will have the same names but different guard
        $adminPermViewBooks = Permission::firstOrCreate(['name' => 'view books', 'guard_name' => 'admins']);
        $adminPermCreateBooks = Permission::firstOrCreate(['name' => 'create books', 'guard_name' => 'admins']);
        $adminPermEditBooks = Permission::firstOrCreate(['name' => 'edit books', 'guard_name' => 'admins']);
        $adminPermDeleteBooks = Permission::firstOrCreate(['name' => 'delete books', 'guard_name' => 'admins']);
        $adminPermManageUsers = Permission::firstOrCreate(['name' => 'manage users', 'guard_name' => 'admins']);
        $adminPermManageRoles = Permission::firstOrCreate(['name' => 'manage roles', 'guard_name' => 'admins']);
        $adminPermAssignRoles = Permission::firstOrCreate(['name' => 'assign roles', 'guard_name' => 'admins']);

        // Admin Role (admins guard)
        $adminRole = Role::firstOrCreate(['name' => 'Admin', 'guard_name' => 'admins']);
        // Assign all 'admins' guard permissions to the 'Admin' role
        $adminPermissions = [
            $adminPermViewBooks,
            $adminPermCreateBooks,
            $adminPermEditBooks,
            $adminPermDeleteBooks,
            $adminPermManageUsers,
            $adminPermManageRoles,
            $adminPermAssignRoles,
        ];
        $adminRole->syncPermissions($adminPermissions);
    }
}
