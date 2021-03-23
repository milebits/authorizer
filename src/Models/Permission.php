<?php

namespace Milebits\Authorizer\Models;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Milebits\Authorizer\Concerns\HasAction;
use Milebits\Authorizer\Concerns\HasClass;
use Milebits\Eloquent\Filters\Concerns\Enableable;
use Milebits\Eloquent\Filters\Concerns\Nameable;

class Permission extends Model
{
    use SoftDeletes, HasFactory, Nameable, HasAction, HasClass, Enableable;

    /**
     * @return BelongsToMany
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class)->withTimestamps();
    }

    /**
     * @param string|array|Arrayable $class
     * @param string|null $action
     * @param bool $getCollection
     * @param string|null $pluck
     * @return array|Collection
     */
    public static function getByClassAction(string|array|Arrayable $class = '*', ?string $action = null, bool $getCollection = false, string $pluck = null): array|Collection
    {
        if (is_string($class) && Str::of($class)->contains('.')) $class = Str::of($class)->explode('.');
        if ($class instanceof Arrayable) $class = $class->toArray();
        if (is_array($class)) return self::getByClassAction(
            $class[0] ?? $class['class'] ?? '*',
            $class[1] ?? $class['action'] ?? '*',
            $getCollection
        );
        $permissions = Permission::action($action)->class($class)->get();
        if (!is_null($pluck)) $permissions = $permissions->pluck($pluck);
        return $getCollection ? $permissions : $permissions->toArray();
    }
}
