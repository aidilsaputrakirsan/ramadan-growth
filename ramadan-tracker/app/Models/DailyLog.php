<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

class DailyLog extends Model
{
    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'user_id',
        'date',
        'tasks_completed',
        'is_perfect_day',
    ];

    /**
     * The attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'date' => 'date',
            'tasks_completed' => 'array',
            'is_perfect_day' => 'boolean',
        ];
    }

    /**
     * Get the user that owns the daily log.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope a query to only include logs for a specific user.
     */
    public function scopeForUser(Builder $query, int $userId): Builder
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope a query to only include perfect days.
     */
    public function scopePerfectDays(Builder $query): Builder
    {
        return $query->where('is_perfect_day', true);
    }
}
