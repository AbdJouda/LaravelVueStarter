<?php

namespace App\Concerns;

use Closure;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

trait HasModelMacros
{
    /**
     * Conditionally eager load relations or relation counts on the model
     *
     * @param bool|Closure $condition
     * @param array|string $relations
     * @param string $method
     * @return $this
     */
    protected function loadIfCondition(bool|Closure $condition, array|string $relations, string $method): static
    {
        $condition = $condition instanceof Closure ? $condition($this) : $condition;

        if (!$condition) {
            return $this;
        }

        return $this->{$method}(Arr::flatten((array)$relations));
    }

    /**
     * Conditionally eager load relations on the model
     *
     * @param bool|Closure $condition
     * @param array|string $relations
     * @return $this
     */
    public function loadIf(bool|Closure $condition, array|string $relations): static
    {
        return $this->loadIfCondition($condition, $relations, 'load');
    }

    /**
     * Conditionally eager load relation counts on the model
     *
     * @param bool|Closure $condition
     * @param array|string $relations
     * @return $this
     */
    public function loadCountIf(bool|Closure $condition, array|string $relations): static
    {
        return $this->loadIfCondition($condition, $relations, 'loadCount');
    }
}
