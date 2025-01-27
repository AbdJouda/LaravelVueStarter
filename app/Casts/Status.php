<?php

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

class Status implements CastsAttributes
{
    /**
     * Create new instance
     *
     * @return void
     */
    public function __construct(protected ?string $onStatus = null, protected ?string $offStatus = null)
    {
    }

    /**
     * Cast the given value.
     *
     * @param array<string, mixed> $attributes
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        return [
            'row' => (bool)$value,
            'text' => $value ? __($this->onStatus ?? 'Active') : __($this->offStatus ?? 'Inactive')
        ];
    }

    /**
     * Prepare the given value for storage.
     *
     * @param array<string, mixed> $attributes
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        return $value;
    }
}
