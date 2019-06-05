<?php

use App\Models\Trust\Role;
use Illuminate\Database\Seeder;
use App\Models\Trust\Permission;

class UsersTableSeeder extends Seeder
{
    /**
     * Auto generated seed file.
     *
     * @return void
     */
    public function run()
    {
        // find or create user admin
        $user = \App\User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Super Admin',
                'password' => bcrypt('123123'),
                'remember_token' => str_random(60),
                'first_workday' => '2008-02-28 07:45:00',
                'team_id' => '12',
                'position_id' => '1',
            ]
        );

        // find or create role admin
        $roleSuperAdmin = Role::firstOrCreate(
            ['name' => Role::SUPER_ADMIN],
            [
                'display_name' => 'Administrator',
                'description' => 'User is allowed to manage all system.',
            ]
        );

        // attach roles
        if (!$user->hasRole(Role::SUPER_ADMIN)) {
            $user->attachRole($roleSuperAdmin);
        }

        //Set Role Permissions
        //Get all permission, swift through and attach them to the role
        $permission = Permission::get();
        foreach ($permission as $key => $value) {
            $roleSuperAdmin->attachPermission($value);
        }


                // find or create user PM
        $user1 = \App\User::firstOrCreate(
            ['email' => 'pm@example.com'],
            [
                'name' => 'Project Management',
                'password' => bcrypt('123123'),
                'remember_token' => str_random(60),
                'first_workday' => '2008-02-28 07:45:00',
                'team_id' => '11',
                'position_id' => '1',
            ]
        );

        // find or create role admin
        $rolePM = Role::firstOrCreate(
            ['name' => Role::PM],
            [
                'display_name' => 'Project Management',
                'description' => 'Users can approve or disapprove employee registration.',
            ]
        );

        // attach roles
        if (!$user1->hasRole(Role::PM)) {
            $user1->attachRole($rolePM);
        }

        $per1 = Permission::where('id', '9')->get()->first();
        $rolePM->attachPermission($per1);

                // find or create user Tech Lead
        $user2 = \App\User::firstOrCreate(
            ['email' => 'techlead@example.com'],
            [
                'name' => 'Tech Lead',
                'password' => bcrypt('123123'),
                'remember_token' => str_random(60),
                'first_workday' => '2008-02-28 07:45:00',
                'team_id' => '11',
                'position_id' => '1',
            ]
        );

        // find or create role admin
        $roleTechLead = Role::firstOrCreate(
            ['name' => Role::TECH_LEAD],
            [
                'display_name' => 'Tech Lead',
                'description' => 'Users can approve or disapprove employee registration.',
            ]
        );

        // attach roles
        if (!$user2->hasRole(Role::TECH_LEAD)) {
            $user2->attachRole($roleTechLead);
        }

        $per2 = Permission::where('id', '10')->get()->first();
        $roleTechLead->attachPermission($per2);

                        // find or create user Member
        $user3 = \App\User::firstOrCreate(
            ['email' => 'member@example.com'],
            [
                'name' => 'member',
                'password' => bcrypt('123123'),
                'remember_token' => str_random(60),
                'first_workday' => '2008-02-28 07:45:00',
                'team_id' => '1',
                'position_id' => '1',
            ]
        );

        // find or create role admin
        $roleMember = Role::firstOrCreate(
            ['name' => Role::MEMBER],
            [
                'display_name' => 'Member',
                'description' => 'Normal users',
            ]
        );

        // attach roles
        if (!$user3->hasRole(Role::MEMBER)) {
            $user3->attachRole($roleMember);
        }
    }
}
