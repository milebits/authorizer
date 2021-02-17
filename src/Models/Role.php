<?php

namespace Milebits\Authorizer\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Milebits\Eloquent\Filters\Concerns\Nameable;
use Milebits\Eloquent\Filters\Concerns\Sluggable;

class Role extends Model
{
    use SoftDeletes, HasFactory, Sluggable, Nameable;

    /**
     * @return BelongsToMany
     */
    public function permissions()
    {
        return $this->belongsToMany(Permission::class)->withTimestamps();
    }

    /**
     * @return BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(config('authorizer.pivots.user_class', 'App\Models\User') ?? 'App\Models\User')->withTimestamps();
    }
}
