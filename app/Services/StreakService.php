<?php

namespace App\Services;

use App\Models\DailyLog;
use Carbon\Carbon;

class StreakService
{
    /**
     * Get the current streak of consecutive perfect days for a user.
     * The streak counts back from the current date.
     *
     * OPTIMIZED: Single query instead of N+1 queries (was: 1 query per day)
     */
    public function getCurrentStreak(int $userId): int
    {
        $today = Carbon::today();

        // Fetch all perfect days in ONE query, ordered by date descending
        // Limit to last 60 days for performance (realistic streak limit)
        $perfectDays = DailyLog::forUser($userId)
            ->perfectDays()
            ->where('date', '<=', $today)
            ->where('date', '>=', $today->copy()->subDays(60))
            ->orderBy('date', 'desc')
            ->pluck('date')
            ->map(fn($date) => Carbon::parse($date)->toDateString());

        if ($perfectDays->isEmpty()) {
            return 0;
        }

        $streak = 0;
        $checkDate = $today->copy();

        // If today is not a perfect day, start from yesterday
        if (!$perfectDays->contains($checkDate->toDateString())) {
            $checkDate->subDay();
        }

        // Count consecutive days in memory (no more queries!)
        while ($perfectDays->contains($checkDate->toDateString())) {
            $streak++;
            $checkDate->subDay();
        }

        return $streak;
    }

    /**
     * Get the longest streak ever for a user.
     */
    public function getLongestStreak(int $userId): int
    {
        $logs = DailyLog::forUser($userId)
            ->orderBy('date', 'asc')
            ->get();

        $longestStreak = 0;
        $currentStreak = 0;
        $previousDate = null;

        foreach ($logs as $log) {
            if ($log->is_perfect_day) {
                // Check if this is consecutive
                if ($previousDate === null || $log->date->diffInDays($previousDate) === 1) {
                    $currentStreak++;
                } else {
                    // Not consecutive, start new streak
                    $currentStreak = 1;
                }

                $longestStreak = max($longestStreak, $currentStreak);
                $previousDate = $log->date;
            } else {
                // Reset streak
                $currentStreak = 0;
                $previousDate = null;
            }
        }

        return $longestStreak;
    }
}
