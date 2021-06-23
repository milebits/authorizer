<?php

namespace Milebits\Authorizer\Models;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;
use Milebits\Authorizer\Concerns\HasAction;
use Milebits\Authorizer\Concerns\HasClass;
use Milebits\Eloquent\Filters\Concerns\EnableField;
use Milebits\Eloquent\Filters\Concerns\NameField;
use Milebits\Eloquent\Filters\Filters\EnableFilter;
use Milebits\Eloquent\Filters\Filters\NameFilter;
use Str;

class Permission extends Model
{
    use SoftDeletes, HasFactory, NameField, HasAction, HasClass, EnableField;

    protected static array $filters = [NameFilter::class, EnableFilter::class];

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
     * @param string|bool $pluck
     * @return array|Collection
     */
    public static function getByClassAction(string|array|Arrayable $class = '*', ?string $action = '*', bool $getCollection = false, string|bool $pluck = true): array|Collection
    {
        if (is_string($class) && Str::of($class)->contains('.')) $class = Str::of($class)->explode('.');
        if ($class instanceof Arrayable) $class = $class->toArray();
        if (is_array($class)) return self::getByClassAction(
            $class[0] ?? $class['class'] ?? '*',
            $class[1] ?? $class['action'] ?? '*',
            $getCollection
        );
        $builder = Permission::action($action)->class($class);
        if (is_bool($pluck) && $pluck) $permissions = $builder->pluck('id');
        else if (is_string($pluck) && Str::of($pluck)->isNotEmpty()) $permissions = $builder->pluck($pluck);
        else $permissions = $builder->get();
        return $getCollection ? $permissions : $permissions->toArray();
    }
}
