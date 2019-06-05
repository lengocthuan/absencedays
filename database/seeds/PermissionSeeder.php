<?php

use Illuminate\Database\Seeder;
use App\Models\Trust\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permission = [
            [
                'name' => 'role-create',
                'display_name' => 'Create Role',
                'description' => 'Create New Role'
            ],
            [
                'name' => 'role-list',
                'display_name' => 'Display Role Listing',
                'description' => 'List All Roles'
            ],
            [
                'name' => 'role-update',
                'display_name' => 'Update Role',
                'description' => 'Update Role Information'
            ],
            [
                'name' => 'role-delete',
                'display_name' => 'Delete Role',
                'description' => 'Delete Role'
            ],
            [
                'name' => 'user-create',
                'display_name' => 'Create User',
                'description' => 'Create New User'
            ],
            [
                'name' => 'user-list',
                'display_name' => 'Display User Listing',
                'description' => 'List All Users'
            ],
            [
                'name' => 'user-update',
                'display_name' => 'Update User',
                'description' => 'Update User Information'
            ],
            [
                'name' => 'user-delete',
                'display_name' => 'Delete User',
                'description' => 'Delete User'
            ],
            [
                'name' => 'pm-approved',
                'display_name' => 'Create PM',
                'description' => 'Create New Permission for PM'
            ],
            [
                'name' => 'techlead-approved',
                'display_name' => 'Create TechLead',
                'description' => 'Create New Permission for TechLead'
            ]
        ];

        foreach ($permission as $key => $value) {
            Permission::create($value);
        }
    }
}
