<?php


namespace Milebits\Authorizer\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use function Milebits\Helpers\Helpers\constVal;

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
        return count((array)(property_exists($builder, 'joins') ? $builder->joins : [])) > 0
            ? $this->getQualifiedClassColumn()
            : $this->getClassColumn();
    }
}
