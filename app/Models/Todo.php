<?php

namespace App\Models;

use App\Concerns\HasSearchables;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Todo extends Model
{
    use HasFactory, HasUuids, HasSearchables;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array<string>|bool
     */
    protected $guarded = ['id'];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return ['is_completed' => 'boolean', 'due_date' => 'date'];
    }

    /**
     * Get the Searchable Attributes.
     *
     * @return array
     */
    public function getSearchables(): array
    {
        return ['title', 'description'];
    }

    /**
     * Define user relation
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
