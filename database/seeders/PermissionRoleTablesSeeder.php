<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PermissionGroup;
use App\Models\Permission;
use App\Models\Role;

class PermissionRoleTablesSeeder extends Seeder
{
	/**
     * @var ;
     */
    private $role;

    /**
     * @var ;
     */
    private $permissions;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	// cria os papéis
    	$this->createRoles();

    	// cria os grupos de persmissões
        $this->createPermissionGroups();

        // cria as permissões
        $this->createPermissions();

        // vincula as permissões aos papéis
        $this->sync();
    }

    private function createRoles()
    {
        Role::create([
            'name' => 'Developer',
            'label'  => 'System Developer',
            'role_id' => 1,
        ]);

        Role::create([
            'name' => 'Administrators',
            'label'  => 'System Administrators',
            'role_id' => 2,
        ]);

        $this->command->info('Roles created!');
    }

    private function createPermissionGroups()
    {
        PermissionGroup::create([
            'name' => 'Developer Settings', //1
            'permission_group_id' => 1,
        ]);

        PermissionGroup::create([
            'name' => 'System Settings', //2
            'permission_group_id' => 2,
        ]);

        PermissionGroup::create([
            'name' => 'Users', //3
            'permission_group_id' => 3,
        ]);

        PermissionGroup::create([
            'name' => 'Permissions', //4
            'permission_group_id' => 4,
        ]);

        $this->command->info('Permission Groups created!');
    }

    private function createPermissions()
    {
        Permission::create([
            'permission_group_id' => 1,
            'name' => 'root-dev',
            'label'  => 'Super Admin Permission',
            'permission_id' => 1,
        ]);

    	Permission::create([
            'permission_group_id' => 2,
            'name' => 'edit-config',
            'permission_id' => 2,
            'label'  => 'Edit System Settings'
        ]);

        Permission::create([
            'permission_group_id' => 3,
            'name' => 'show-user',
            'permission_id' => 3,
            'label'  => 'View User'
        ]);

        Permission::create([
            'permission_group_id' => 3,
            'name' => 'create-user',
            'permission_id' => 4,
            'label'  => 'Add User'
        ]);

        Permission::create([
            'permission_group_id' => 3,
            'name' => 'edit-user',
            'permission_id' => 5,
            'label'  => 'Edit User'
        ]);

        Permission::create([
            'permission_group_id' => 3,
            'name' => 'destroy-user',
            'permission_id' => 6,
            'label'  => 'Delete User'
        ]);

        Permission::create([
            'permission_group_id' => 4,
            'name' => 'show-role',
            'permission_id' => 7,
            'label'  => 'View Permission'
        ]);

        Permission::create([
            'permission_group_id' => 4,
            'name' => 'create-role',
            'permission_id' => 8,
            'label'  => 'Add Permission'
        ]);

        Permission::create([
            'permission_group_id' => 4,
            'name' => 'edit-role',
            'permission_id' => 9,
            'label'  => 'Edit Permission'
        ]);

        Permission::create([
            'permission_group_id' => 4,
            'name' => 'destroy-role',
            'permission_id' => 10,
            'label'  => 'Delete Permission'
        ]);

        $this->command->info('Permissions created!');
    }

    private function sync()
    {
         $permissions_id = Permission::permissionsId(1);

         $role = Role::find(1);
         $role->permissions()->sync($permissions_id);

         $permissions_id = Permission::permissionsId(2);
         $role = Role::find(2);
         $role->permissions()->sync($permissions_id);

        $this->command->info('Persistence linked to roles!');
    }
}
