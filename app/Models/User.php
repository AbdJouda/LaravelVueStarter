<?php

namespace App\Models;

use App\Concerns\HasAccessCodes;
use App\Concerns\HasActiveAttribute;
use App\Concerns\HasFileUpload;
use App\Concerns\HasSearchables;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory,
        Notifiable,
        HasApiTokens,
        HasFileUpload,
        HasActiveAttribute,
        HasUuids,
        HasSearchables,
        HasRoles,
        HasAccessCodes,
        SoftDeletes;

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
    protected $hidden = ['password'];

    /**
     * Get the Searchable Attributes.
     *
     * @return array
     */
    public function getSearchables(): array
    {
        return ['name', 'email', 'phone'];
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return ['email_verified_at' => 'datetime', 'phone_verified_at' => 'datetime', 'password' => 'hashed'];
    }

    /**
     * Define Deletion Log Relation
     *
     * @return HasOne
     */
    public function deletionLog(): HasOne
    {
        return $this->hasOne(DeletionLog::class);
    }

    /**
     * Check if user has new notifications
     *
     * @return bool
     */
    public function getHasNewNotificationsAttribute(): bool
    {
        return $this->unreadNotifications()->exists();
    }

    /**
     * Define Todo relation
     *
     * @return HasMany
     */
    public function todos(): HasMany
    {
        return $this->hasMany(Todo::class);
    }


}
