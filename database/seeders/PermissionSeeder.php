<?php

namespace Milebits\Authorizer\Database\seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Milebits\Authorizer\Models\Permission;
use function app_models;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        app_models()->each(function ($model) {
            collect(['viewAny' => "View any", 'view' => "View", "create" => "Create", 'update' => "Update", 'delete' => "Delete", 'forceDelete' => "Force delete", "restore" => "Restore"])
                ->each(function ($name, $verb) use ($model) {
                    $modelName = Str::of(class_basename($model))->singular()->snake()->lower();
                    Permission::create([
                        "name" => "$name $modelName",
                        "class" => ltrim(rtrim($model, '\\'), '\\'),
                        "action" => $verb,
                        "enable" => true,
                    ]);
                });
        });
    }
}
