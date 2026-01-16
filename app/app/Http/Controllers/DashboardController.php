<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct(
        protected \App\Services\PerfectDayCalculator $calculator,
        protected \App\Services\StreakService $streakService
    ) {}

    /**
     * Display the dashboard.
     */
    public function index(Request $request): \Inertia\Response
    {
        $user = $request->user();
        $today = now()->format('Y-m-d');

        // Get or create empty log for today (don't save yet, just for display)
        $todayLog = \App\Models\DailyLog::forUser($user->id)
            ->where('date', $today)
            ->first();

        $totalPerfectDays = $this->calculator->getTotalPerfectDays($user->id);
        $currentStreak = $this->streakService->getCurrentStreak($user->id);
        $masjidStage = $this->calculator->getMasjidStage($totalPerfectDays);

        return \Inertia\Inertia::render('Dashboard', [
            'todayLog' => $todayLog,
            'totalPerfectDays' => $totalPerfectDays,
            'currentStreak' => $currentStreak,
            'masjidStage' => $masjidStage,
        ]);
    }
}
