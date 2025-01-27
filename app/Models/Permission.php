<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Permission as SpatiePermission;

class Permission extends SpatiePermission
{
    use HasFactory, HasUuids;

   /**
    * The attributes that aren't mass assignable.
    *
    * @var array<string>|bool
    */
    protected $guarded = ['id'];

    /**
     * Get Display Name For Permissions
     */
    protected function displayName(): Attribute
    {
        return Attribute::make(
            get: fn () => ucwords(str_replace('_', ' ', $this->attributes['name'])),
        );
    }
}
