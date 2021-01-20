<?php

namespace Milebits\Authorizer\Helpers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use ReflectionClass;

if (!function_exists('get_class_from_file')) {
    function get_class_from_file($filepath)
    {
        // this assumes you're following PSR-4 standards, although you may
        // still need to modify based on how you're structuring your app/namespaces
        return (string)Str::of($filepath)
            ->replace(app_path(), '\App')
            ->replaceFirst('app', 'App')
            ->replaceLast('.php', '')
            ->replace('/', '\\');
    }
}

if (!function_exists('app_models')) {
    function app_models($path = null, $base_model = null, bool $with_abstract = false)
    {
        $disk = Storage::disk('app');
        if (is_null($path) && $disk->exists('Models')) $path = "Models";
        return collect($disk->allFiles($path))
            ->map(function ($filename) use ($disk) {
                return get_class_from_file($disk->path($filename));
            })
            ->filter(function ($class) use ($base_model, $with_abstract) {
                $ref = new ReflectionClass($class);
                if ($ref->isAbstract() && !$with_abstract) return false;
                return $ref->isSubclassOf($base_model ?? Model::class);
            });

    }
}
