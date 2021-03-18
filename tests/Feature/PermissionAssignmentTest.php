<?php

namespace Milebits\Authoriser\Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Milebits\Authoriser\Tests\TestCase;
use Milebits\Authorizer\Models\Permission;
use Milebits\Authorizer\Models\Role;

/**
 * Class PermissionAssignmentTest
 * @package Milebits\Authoriser\Tests\Feature
 */
class PermissionAssignmentTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @return User
     */
    public function noTest_BuildPermissions(): User
    {
        Permission::create(['name' => 'View any roles', 'class' => Role::class, 'action' => 'viewAny']);
        Permission::create(['name' => 'View roles', 'class' => Role::class, 'action' => 'view']);
        Permission::create(['name' => 'Create roles', 'class' => Role::class, 'action' => 'create']);
        Permission::create(['name' => 'Update roles', 'class' => Role::class, 'action' => 'update']);
        Permission::create(['name' => 'Delete roles', 'class' => Role::class, 'action' => 'delete']);
        Permission::create(['name' => 'Force delete roles', 'class' => Role::class, 'action' => 'forceDelete']);

        Permission::create(['name' => 'View any users', 'class' => 'App\Models\User', 'action' => 'viewAny']);
        Permission::create(['name' => 'View users', 'class' => 'App\Models\User', 'action' => 'view']);
        Permission::create(['name' => 'Create users', 'class' => 'App\Models\User', 'action' => 'create']);
        Permission::create(['name' => 'Update users', 'class' => 'App\Models\User', 'action' => 'update']);
        Permission::create(['name' => 'Delete users', 'class' => 'App\Models\User', 'action' => 'delete']);
        Permission::create(['name' => 'Force delete users', 'class' => 'App\Models\User', 'action' => 'forceDelete']);

        $role = Role::create(['name' => 'Dummy Role', 'slug' => 'dummy', 'enabled' => true]);
        $user = User::create(['name' => 'Dummy User', 'email' => 'dummy@dummy.com', 'password' => Hash::make('password')]);
        $this->app['config']->set('authorizer.pivots.user_class', User::class);
        $role->users()->attach($user);
        return $user;
    }

    public function test_permissions_can_be_found_by_class_action_method()
    {
        $this->noTest_BuildPermissions();
        $permission = Permission::findByClassAction(Role::class, 'viewAny');
        self::assertNotNull($permission);
        $permission = Permission::findByClassAction('App\Models\User.viewAny');
        self::assertNotNull($permission);
    }

    public function test_permissions_check_at_once()
    {
        $permissions = [
            ['App\Models\User', 'view'],
            ['App\Models\User', '*'],
            collect([Role::class, 'view']),
            collect([Role::class, '*']),
            ['*', 'viewAny'],
            ['*', '*'],
        ];
        self::assertFalse($this->noTest_BuildPermissions()->hasPermissions($permissions));
    }
}
