<?php

namespace App\Services;

use App\Models\DailyLog;
use Carbon\Carbon;

class StreakService
{
    /**
     * Get the current streak of consecutive perfect days for a user.
     * The streak counts back from the current date.
     */
    public function getCurrentStreak(int $userId): int
    {
        $today = Carbon::today();
        $streak = 0;
        $checkDate = $today->copy();

        // Check each day going backwards
        while (true) {
            $log = DailyLog::forUser($userId)
                ->where('date', $checkDate->toDateString())
                ->first();

            // If no log for this day or not a perfect day, check if we should continue
            if (!$log || !$log->is_perfect_day) {
                // If today is not a perfect day yet, check from yesterday
                if ($checkDate->equalTo($today) && $streak === 0) {
                    $checkDate->subDay();
                    continue;
                }
                // Otherwise, streak is broken
                break;
            }

            // This day is a perfect day
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
