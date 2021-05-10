<?php

namespace Milebits\Authorizer\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Milebits\Eloquent\Filters\Concerns\EnableField;
use Milebits\Eloquent\Filters\Concerns\NameField;
use Milebits\Eloquent\Filters\Concerns\SlugField;

class Role extends Model
{
    use SoftDeletes, HasFactory, SlugField, NameField, EnableField;

    /**
     * @return BelongsToMany
     */
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class)->withTimestamps();
    }

    /**
     * @return BelongsToMany
     */
    public function users(): BelongsToMany
    {
        $relation = config('authorizer.pivots.user_class', 'App\Models\User');
        return $this->belongsToMany(is_null($relation) ? 'App\Models\User' : $relation)->withTimestamps();
    }
}
