<?php

namespace Milebits\Authorizer\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use function Milebits\Helpers\Helpers\constVal;

/**
 * Trait HasAction
 * @package Milebits\Authorizer\Concerns
 * @mixin Model
 */
trait HasAction
{
    public function initializeHasAction(): void
    {
        $this->mergeFillable([$this->getActionColumn()]);
    }

    /**
     * @return string
     */
    public function getActionColumn(): string
    {
        return constVal($this, 'ACTION_COLUMN', 'action');
    }

    /**
     * @param Builder $builder
     * @param string $action
     * @return Builder
     */
    public function scopeAction(Builder $builder, string $action): Builder
    {
        return $builder->where(
            count((array)(property_exists($builder, 'joins') ? $builder->joins : [])) > 0
                ? $this->getQualifiedActionColumn()
                : $this->getActionColumn(),
            $action
        );
    }

    /**
     * @return string
     */
    public function getQualifiedActionColumn(): string
    {
        return $this->qualifyColumn($this->getActionColumn());
    }
}
