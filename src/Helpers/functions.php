<?php

use Illuminate\Database\Eloquent\Model;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

if (!function_exists('get_class_from_file')) {
    function get_class_from_file($filepath): string
    {
        // this assumes you're following PSR-4 standards, although you may
        // still need to modify based on how you're structuring your app/namespaces
        return (string)Str::of($filepath)
            ->replace(app_path(), 'App')
            ->replaceFirst('app', 'App')
            ->replaceLast('.php', '')
            ->replace('/', '\\');
    }
}

if (!function_exists('app_models')) {
    function app_models($path = null, $base_model = null, bool $with_abstract = false): Collection
    {
        $fileSystem = new Filesystem();
        $path = $fileSystem->exists($modelsPath = $path ?: app_path('Models')) ? $modelsPath : app_path();
        return collect($fileSystem->allFiles($path))
            ->map(function ($filename) use ($fileSystem) {
                return get_class_from_file(app_path("Model\\$filename"));
            })
            ->filter(function ($class) use ($base_model, $with_abstract) {
                $ref = new ReflectionClass($class);
                if ($ref->isAbstract() && !$with_abstract) return false;
                return $ref->isSubclassOf($base_model ?? Model::class);
            });

    }
}
