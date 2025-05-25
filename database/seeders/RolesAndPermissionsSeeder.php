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
        
        // New WebContent permissions for 'web' guard
        $webPermViewWebContent = Permission::firstOrCreate(['name' => 'view web_content', 'guard_name' => 'web']);
        $webPermCreateWebContent = Permission::firstOrCreate(['name' => 'create web_content', 'guard_name' => 'web']);
        $webPermEditWebContent = Permission::firstOrCreate(['name' => 'edit web_content', 'guard_name' => 'web']);
        $webPermDeleteWebContent = Permission::firstOrCreate(['name' => 'delete web_content', 'guard_name' => 'web']);

        // Viewer Role (web guard)
        $viewerRole = Role::firstOrCreate(['name' => 'Viewer', 'guard_name' => 'web']);
        $viewerRole->givePermissionTo([
            $webPermViewBooks,
            $webPermViewWebContent, // Assign new permission
        ]);

        // Editor Role (web guard)
        $editorRole = Role::firstOrCreate(['name' => 'Editor', 'guard_name' => 'web']);
        $editorRole->givePermissionTo([
            $webPermViewBooks,
            $webPermCreateBooks,
            $webPermEditBooks,
            $webPermDeleteBooks,
            $webPermViewWebContent, // Assign new permissions
            $webPermCreateWebContent,
            $webPermEditWebContent,
            $webPermDeleteWebContent,
        ]);

        // Permissions for 'admins' guard (for Admin model)
        $adminPermViewBooks = Permission::firstOrCreate(['name' => 'view books', 'guard_name' => 'admins']);
        $adminPermCreateBooks = Permission::firstOrCreate(['name' => 'create books', 'guard_name' => 'admins']);
        $adminPermEditBooks = Permission::firstOrCreate(['name' => 'edit books', 'guard_name' => 'admins']);
        $adminPermDeleteBooks = Permission::firstOrCreate(['name' => 'delete books', 'guard_name' => 'admins']);
        $adminPermManageUsers = Permission::firstOrCreate(['name' => 'manage users', 'guard_name' => 'admins']);
        $adminPermManageRoles = Permission::firstOrCreate(['name' => 'manage roles', 'guard_name' => 'admins']);
        $adminPermAssignRoles = Permission::firstOrCreate(['name' => 'assign roles', 'guard_name' => 'admins']);

        // New WebContent permissions for 'admins' guard
        $adminPermViewWebContent = Permission::firstOrCreate(['name' => 'view web_content', 'guard_name' => 'admins']);
        $adminPermCreateWebContent = Permission::firstOrCreate(['name' => 'create web_content', 'guard_name' => 'admins']);
        $adminPermEditWebContent = Permission::firstOrCreate(['name' => 'edit web_content', 'guard_name' => 'admins']);
        $adminPermDeleteWebContent = Permission::firstOrCreate(['name' => 'delete web_content', 'guard_name' => 'admins']);

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
            $adminPermViewWebContent, // Add new permissions
            $adminPermCreateWebContent,
            $adminPermEditWebContent,
            $adminPermDeleteWebContent,
        ];
        $adminRole->syncPermissions($adminPermissions);
    }
}
