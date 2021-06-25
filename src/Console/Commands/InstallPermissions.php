<?php

namespace Milebits\Authorizer\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use JetBrains\PhpStorm\ArrayShape;
use Milebits\Authorizer\Models\Permission;
use function app_models;

class InstallPermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'authorizer:permInstall';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install Authorizer Permissions';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        Permission::all()->each(fn(Permission $permission) => $permission->forceDelete());
        app_models()->each(function ($model) {
            collect($this->getMethods())->each(function ($name, $verb) use ($model) {
                $modelName = Str::of(class_basename($model))->singular()->snake()->lower();
                Permission::create(["class" => $model, "action" => $verb, "enable" => true, 'name' => "$name $modelName"]);
            });
        });
        return 0;
    }

    /**
     * @return string[]
     */
    #[ArrayShape(['viewAny' => "string", 'view' => "string", "create" => "string", 'update' => "string", 'delete' => "string", 'forceDelete' => "string", 'restore' => 'string'])]
    public function getMethods(): array
    {
        return ['viewAny' => "View any", 'view' => "View", "create" => "Create", 'update' => "Update", 'delete' => "Delete", 'forceDelete' => "Force delete", "restore" => "Restore"];
    }
}
