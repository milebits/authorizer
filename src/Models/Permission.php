<?php

namespace Milebits\Authorizer\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Milebits\Authorizer\Concerns\HasAction;
use Milebits\Authorizer\Concerns\HasClass;
use Milebits\Eloquent\Filters\Concerns\Nameable;

class Permission extends Model
{
    use SoftDeletes, HasFactory, Nameable, HasAction, HasClass;

    /**
     * @return BelongsToMany
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class)->withTimestamps();
    }

    /**
     * @param string $class
     * @param string $action
     * @return Permission|null
     */
    public static function findByClassAction(string $class, string $action): ?Permission
    {
        return self::action($action)->class($class)->first();
    }
}
