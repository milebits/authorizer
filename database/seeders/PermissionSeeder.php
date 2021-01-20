<?php

namespace Milebits\Authoriser\Database\seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Milebits\Authorizer\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        collect(app_models())->each(function ($model) {
            collect(config('authorizer.pivots.permissions.models_to_seed', []))->each(function ($name, $verb) use ($model) {
                $model = Str::of(class_basename($model))->singular()->snake()->lower();
                Permission::create(["slug" => "$verb.$model", "enabled" => true, 'name' => "$name $model"]);
            });
        });
    }
}
