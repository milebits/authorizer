<?php


namespace Milebits\Authorizer\Concerns;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
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
     * @return Collection
     */
    public function permissions(): Collection
    {
        return Permission::whereHas('roles', function (Builder $roleBuilder) {
            return $roleBuilder->whereHas('users', function (Builder $userBuilder) {
                return $userBuilder->whereKey($this->getKey());
            });
        })->get();
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
     * @param Model|array|string $class
     * @param string|null $action
     * @return bool
     */
    public function hasPermission(Model|array|string $class, string $action = null): bool
    {
        return $this->roles()->whereHas('permissions', function (Builder $permission) use ($class, $action) {
            if (is_string($class) && Str::contains($class, '.')) [$class, $action] = Str::of($class)->explode('.');
            if (is_array($class)) [$class, $action] = $class;
            if ($class instanceof Model && is_null($action)) return $permission->whereKey($class->getKey());
            if (!is_string($class) || !is_string($action)) return null;
            return $permission->action($action)->class($class);
        })->exists();
    }

    /**
     * @param Collection|array $permissions
     * @param bool $shouldHaveAll
     * @return bool
     */
    public function hasPermissions(Collection|array $permissions, bool $shouldHaveAll = false): bool
    {
        if (is_array($permissions)) $permissions = collect($permissions);
        $permissions = $permissions->transform(function ($permission) {
            if (is_int($permission)) $permission = Permission::find($permission);
            if ($permission instanceof Arrayable) $permission = $permission->toArray();
            if ($permission instanceof Permission) return [$permission->{$permission->getClassColumn()}, $permission->{$permission->getActionColumn()}];
            if (is_array($permission)) return $permission;
            if (is_string($permission)) return Str::of($permission)->explode('.');
            return null;
        });
        $count = $permissions->count();
        $countFound = $permissions->whereNotNull()->reject(fn(array $permission) => !$this->hasPermission($permission))->count();
        return $shouldHaveAll ? $count == $countFound : $countFound > 0;
    }
}
