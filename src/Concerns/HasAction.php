<?php

namespace Milebits\Authorizer\Concerns;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use JetBrains\PhpStorm\Pure;
use function Milebits\Helpers\Helpers\constVal;

/**
 * Trait HasAction
 * @package Milebits\Authorizer\Concerns
 * @mixin Model
 * @method static Builder action(string $action)
 * @method static Builder class(string $class)
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
    #[Pure] public function getActionColumn(): string
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
        if ($action === '*') return $builder;
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

    /**
     * @param Builder $builder
     * @param string|array|Arrayable $actions
     * @param string $glue
     * @return Builder
     */
    public function scopeActionIn(Builder $builder, string|array|Arrayable $actions = [], string $glue = "|"): Builder
    {
        if (is_string($actions)) {
            $actions = Str::of($actions);
            if (($actions = Str::of($actions))->contains($glue)) $actions = $actions->explode($glue);
            if (!is_array($actions)) $actions = [$actions];
        }
        if (is_array($actions)) $actions = collect($actions);
        return $builder->whereIn($this->decideActionColumnName($builder), $actions);
    }
}
