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

    public function scopeClass(Builder $builder, string $class): Builder
    {
        return $builder->where(
            count((array)(property_exists($builder, 'joins') ? $builder->joins : [])) > 0
                ? $this->getQualifiedClassColumn()
                : $this->getClassColumn(),
            $class
        );
    }

    /**
     * @return string
     */
    public function getQualifiedClassColumn(): string
    {
        return $this->qualifyColumn($this->getClassColumn());
    }
}
