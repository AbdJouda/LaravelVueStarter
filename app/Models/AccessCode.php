<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccessCode extends Model
{
    use HasFactory, HasUuids;

    /**
     * Password reset access code target
     */
    const PASSWORD_RESET_CODE = 0;

   /**
    * The attributes that aren't mass assignable.
    *
    * @var array<string>|bool
    */
    protected $guarded = ['id'];

     /**
      * The attributes that should be hidden for serialization.
      *
      * @var list<string>
      */
    protected $hidden = ['code'];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return ['created_at' => 'datetime', 'expires_at' => 'datetime', 'code' => 'hashed'];
    }

    /**
     * Scope only valid codes
     *
     * @param Builder $query
     *
     * @return Builder
     */
    public function scopeValid(Builder $query): Builder
    {
        return $query->where('expires_at', '>=', now());
    }

    /**
     * Get codes for given target
     *
     * @param Builder $query
     * @param string $value
     * @return Builder
     */
    public function scopeFor(Builder $query, string $value): Builder
    {
        return $query->where('target', $value);
    }
}
