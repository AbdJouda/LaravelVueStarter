<?php

namespace App\Models;

use App\Concerns\HasSearchables;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
{
    use HasFactory, HasUuids, HasSearchables;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array<string>|bool
     */
    protected $guarded = ['id'];

    /**
     * Get the Searchable Attributes.
     *
     * @return array
     */
    public function getSearchables(): array
    {
        return [
            'name',
            'permissions' => ['name' => 'default'],
        ];
    }

    /**
     * Get Display Name For Role
     */
    protected function name(): Attribute
    {
        return Attribute::make(
            set: fn($value) => Str::slug($value, '_'),
        );
    }

    /**
     * Get Display Name For Role
     */
    protected function displayName(): Attribute
    {
        return Attribute::make(
            get: fn() => ucwords(str_replace('_', ' ', $this->attributes['name'])),
        );
    }

}
