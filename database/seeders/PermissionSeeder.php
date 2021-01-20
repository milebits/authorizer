<?php

namespace Milebits\Authoriser\Database\seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Milebits\Authorizer\Models\Permission;
use function Milebits\Authorizer\Helpers\app_models;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        app_models()->each(function ($model, $index) {
            collect(['viewAny' => "View any", 'view' => "View", 'update' => "Update", 'delete' => "Delete", 'forceDelete' => "Force delete"])
                ->each(function ($name, $verb) use ($model) {
                    $model = Str::of(class_basename($model))->singular()->snake()->lower();
                    Permission::create(["slug" => "$verb.$model", "enabled" => true, 'name' => "$name $model"]);
                });
        });
    }
}
