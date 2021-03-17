<?php


namespace Milebits\Authorizer\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Milebits\Authorizer\Models\Role;

/**
 * Trait Authorizer
 * @package Milebits\Authorizer\Concerns
 *
 * @mixin Model
 */
trait Authorizer
{
    /**
     * @param Role|int|array $role
     * @return array
     */
    public function addRole(Role|array|int $role): array
    {
        return $this->roles()->syncWithoutDetaching(is_array($role) ? $role : [$role]);
    }

    /**
     * @return BelongsToMany
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class)->withTimestamps();
    }

    /**
     * @param Role|int|array $role
     * @return int
     */
    public function removeRole(Role|array|int $role): int
    {
        return $this->roles()->detach(is_array($role) ? $role : [$role]);
    }

    /**
     * @param Role|int|string $role
     * @return bool
     */
    public function hasRole(Role|int|string $role): bool
    {
        if ($role instanceof Model) return $this->roles()->whereKey($role->getKey())->exists();
        if (is_int($role)) return $this->roles()->whereKey($role)->exists();
        return $this->roles()->whereKey(Role::slug($role)->first())->exists();
    }

    /**
     * @param string|Model $class
     * @param string|null $action
     * @return bool
     */
    public function hasPermission(string|Model $class, string $action = null): bool
    {
        return $this->roles()->whereHas('permissions', function (Builder $permission) use ($class, $action) {
            if (is_array($class))
                [$class, $action] = $class;
            if ($class instanceof Model && is_null($action))
                return $permission->whereKey($class->getKey());
            if (!is_string($class) || !is_string($action)) return null;
            return $permission->action($action)->class($class);
        })->exists();
    }
}
