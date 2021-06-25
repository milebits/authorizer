<?php


namespace Milebits\Authorizer\Concerns;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use function constVal;

/**
 * Trait HasClass
 * @package Milebits\Authorizer\Concerns
 * @mixin Model
 */
trait HasClass
{
    public function initializeHasClass(): void
    {
        $this->mergeFillable([$this->getClassColumn()]);
    }

    /**
     * @return string
     */
    public function getClassColumn(): string
    {
        return constVal($this, 'CLASS_COLUMN', 'class');
    }

    /**
     * @param Builder $builder
     * @param string $class
     * @return Builder
     */
    public function scopeClass(Builder $builder, string $class): Builder
    {
        if ($class == '*') return $builder;
        return $builder->where($this->decideClassColumnName($builder), $class);
    }

    /**
     * @return string
     */
    public function getQualifiedClassColumn(): string
    {
        return $this->qualifyColumn($this->getClassColumn());
    }

    /**
     * @param Builder $builder
     * @return string
     */
    public function decideClassColumnName(Builder $builder): string
    {
        return count(property_exists($builder, 'joins') ? $builder->joins : []) > 0
            ? $this->getQualifiedClassColumn()
            : $this->getClassColumn();
    }

    /**
     * @param Builder $builder
     * @param string|array|Arrayable $classes
     * @param string $glue
     * @return Builder
     */
    public function scopeClassIn(Builder $builder, string|array|Arrayable $classes = [], string $glue = "|"): Builder
    {
        if (is_string($classes)) {
            $classes = Str::of($classes);
            if (($classes = Str::of($classes))->contains($glue)) $classes = $classes->explode($glue);
            if (!is_array($classes)) $classes = [$classes];
        }
        if (is_array($classes)) $classes = collect($classes);
        return $builder->whereIn($this->decideClassColumnName($builder), $classes);
    }
}
