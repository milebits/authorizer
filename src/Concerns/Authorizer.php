<?php


namespace Milebits\Authorizer\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Milebits\Authorizer\Models\Permission;
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
    public function addRole(int|array|Role $role)
    {
        return $this->roles()->syncWithoutDetaching($role);
    }

    /**
     * @return BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class)->withTimestamps();
    }

    /**
     * @param Role|int|array $role
     * @return int
     */
    public function removeRole(int|array|Role $role)
    {
        return $this->roles()->detach($role);
    }

    /**
     * @param Role|int|string $role
     * @return mixed
     */
    public function hasRole(Role|int|string $role)
    {
        if ($role instanceof Model) return $this->roles()->whereKey($role->getKey())->exists();
        if (is_int($role)) return $this->roles()->whereKey($role)->exists();
        return $this->roles()->whereKey(Role::slug($role)->first())->exists();
    }

    /**
     * @param int|string|Permission $perm
     * @return bool
     */
    public function hasPermission(Permission|string|int $perm)
    {
        return $this->roles()->whereHas('permissions', function (Builder $permission) use ($perm) {
            if ($perm instanceof Model)
                return $permission->whereKey($perm->getKey());
            if (is_int($perm))
                return $permission->whereKey($perm);
            return $permission->whereKey(Permission::slugLike($perm)->first());
        })->exists();
    }
}
