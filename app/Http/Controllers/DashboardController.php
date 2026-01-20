<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct(
        protected \App\Services\PerfectDayCalculator $calculator,
        protected \App\Services\StreakService $streakService
    ) {
    }

    /**
     * Display the dashboard.
     */
    public function index(Request $request): \Inertia\Response
    {
        $user = $request->user();

        // Get date from query parameter, default to today
        $date = $request->query('date', now()->format('Y-m-d'));

        // Validate date format and ensure it's not in the future
        try {
            $dateObj = new \DateTime($date);
            $today = new \DateTime(now()->format('Y-m-d'));

            if ($dateObj > $today) {
                $date = $today->format('Y-m-d');
            }
        } catch (\Exception $e) {
            $date = now()->format('Y-m-d');
        }

        // Get or create empty log for the selected date
        $todayLog = \App\Models\DailyLog::forUser($user->id)
            ->where('date', $date)
            ->first();

        $totalPerfectDays = $this->calculator->getTotalPerfectDays($user->id);
        $currentStreak = $this->streakService->getCurrentStreak($user->id);
        $masjidStage = $this->calculator->getMasjidStage($totalPerfectDays);

        return \Inertia\Inertia::render('Dashboard', [
            'todayLog' => $todayLog,
            'totalPerfectDays' => $totalPerfectDays,
            'currentStreak' => $currentStreak,
            'masjidStage' => $masjidStage,
            'selectedDate' => $date,
        ]);
    }
}
