<?php

namespace App\Services;

use App\Models\DailyLog;

class PerfectDayCalculator
{
    /**
     * Check if the given tasks constitute a perfect day.
     * A perfect day is when all required ibadah are completed.
     */
    public function isPerfectDay(array $tasksCompleted): bool
    {
        foreach (\App\Services\IbadahService::REQUIRED_FOR_PERFECT as $task) {
            if (!isset($tasksCompleted[$task]) || $tasksCompleted[$task] !== true) {
                return false;
            }
        }
        return true;
    }

    /**
     * Get the total number of perfect days for a user.
     */
    public function getTotalPerfectDays(int $userId): int
    {
        return DailyLog::forUser($userId)
            ->perfectDays()
            ->count();
    }

    /**
     * Get the masjid evolution stage based on perfect days count.
     * 
     * Stage 1: 0-6 perfect days (Empty land/foundation)
     * Stage 2: 7-14 perfect days (Building structure)
     * Stage 3: 15-21 perfect days (Dome and minaret)
     * Stage 4: 22+ perfect days (Complete mosque)
     */
    public function getMasjidStage(int $perfectDays): int
    {
        if ($perfectDays <= 6) {
            return 1;
        }
        
        if ($perfectDays <= 14) {
            return 2;
        }
        
        if ($perfectDays <= 21) {
            return 3;
        }
        
        return 4;
    }

    /**
     * Get the required tasks list.
     */
    public function getRequiredTasks(): array
    {
        return \App\Services\IbadahService::REQUIRED_FOR_PERFECT;
    }
}
