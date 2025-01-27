<?php

namespace App\Concerns;

use App\Casts\Status;
use Illuminate\Database\Eloquent\Builder;

trait HasActiveAttribute
{
    /**
     * Toggle the active status of the model.
     *
     * @return string
     */
    public function toggleActiveStatus(): string
    {
        $this->forceFill(['is_active' => !$this->isActive()])->save();

        return $this->isActive()
            ? __('actions.success.activate', ['item' => __(class_basename($this))])
            : __('actions.success.deactivate', ['item' => __(class_basename($this))]);
    }

    /**
     * Check if model is active
     *
     * @return bool
     */
    public function isActive(): bool
    {
        return is_array($this->is_active)
            ? data_get($this->is_active, 'row')
            : $this->is_active;
    }

    /**
     * @inheritDoc
     *
     * @return array
     */
    public function getCasts()
    {
        if ($this->getIncrementing()) {
            return array_merge([$this->getKeyName() => $this->getKeyType()], $this->casts);
        }

        return array_merge($this->casts, [$this->getActivationColumn() => Status::class]);
    }

    /**
     * Get activation column name
     *
     * @return string
     */
    private function getActivationColumn(): string
    {
        return 'is_active';
    }

    /**
     * Scope a query to retrieve only active records.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

}
