<?php

namespace Milebits\Authorizer\Database\seeders;

use Illuminate\Database\Seeder;
use Milebits\Authorizer\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::create(['slug' => 'admin', 'name' => 'Administrator', 'enable' => true]);
        Role::create(['slug' => 'moderator', 'name' => 'Moderator', 'enable' => true]);
        Role::create(['slug' => 'manager', 'name' => 'Manager', 'enable' => true]);
        Role::create(['slug' => 'employee', 'name' => 'Employee', 'enable' => true]);
        Role::create(['slug' => 'partner', 'name' => 'Partner', 'enable' => true]);
    }
}
