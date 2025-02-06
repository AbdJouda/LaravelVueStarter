<?php

namespace App\Models;

use App\Concerns\HasSearchables;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

    /**
     * Scope Upcoming Tasks
     *
     * @returns void
     */
    public function scopeUpcoming(Builder $query): void
    {
        $query->whereDate('due_date', '>=', new Carbon())
        ->orWhereNull('due_date');
    }

    /**
     * Scope Non completed Tasks
     *
     * @returns void
     */
    public function scopeNonCompleted(Builder $query): void
    {
        $query->where('is_completed', false);
    }
}
