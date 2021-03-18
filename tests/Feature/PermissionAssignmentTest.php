<?php

namespace Milebits\Authoriser\Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
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

    public function test_permissions_can_be_found_by_class_action_method()
    {
        Permission::create([
            'name' => 'View any roles',
            'class' => Role::class,
            'action' => 'viewAny'
        ]);
        $permission = Permission::findByClassAction(Role::class, 'viewAny');
        self::assertNotNull($permission);
    }
}
