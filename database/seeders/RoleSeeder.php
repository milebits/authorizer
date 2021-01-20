<?php

namespace Milebits\Authoriser\Database\Seeders;

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
        Role::create(['slug' => 'admin', 'name' => 'Administrator', 'enabled' => true]);
        Role::create(['slug' => 'moderator', 'name' => 'Moderator', 'enabled' => true]);
        Role::create(['slug' => 'manager', 'name' => 'Manager', 'enabled' => true]);
        Role::create(['slug' => 'employee', 'name' => 'Employee', 'enabled' => true]);
        Role::create(['slug' => 'partner', 'name' => 'Partner', 'enabled' => true]);
    }
}
