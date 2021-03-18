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
        if ($action === '*')
            return $builder->where($this->decideActionColumnName($builder), 'like', '%%');
        return $builder->where($this->decideActionColumnName($builder), $action);
    }

    /**
     * @return string
     */
    public function getQualifiedActionColumn(): string
    {
        return $this->qualifyColumn($this->getActionColumn());
    }

    /**
     * @param Builder $builder
     * @return string
     */
    public function decideActionColumnName(Builder $builder): string
    {
        return count((array)(property_exists($builder, 'joins') ? $builder->joins : [])) > 0
            ? $this->getQualifiedActionColumn()
            : $this->getActionColumn();
    }
}
