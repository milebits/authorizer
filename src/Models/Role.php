<?php

namespace Milebits\Authorizer\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Milebits\Eloquent\Filters\Concerns\EnableField;
use Milebits\Eloquent\Filters\Concerns\NameField;
use Milebits\Eloquent\Filters\Concerns\SlugField;
use Milebits\Eloquent\Filters\Filters\EnableFilter;
use Milebits\Eloquent\Filters\Filters\NameFilter;
use Milebits\Eloquent\Filters\Filters\SlugFilter;

class Role extends Model
{
    use SoftDeletes, HasFactory, SlugField, NameField, EnableField;

    protected static array $filters = [SlugFilter::class, NameFilter::class, EnableFilter::class];

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
        return $this->belongsToMany($relation ?: 'App\Models\User')->withTimestamps();
    }
}
