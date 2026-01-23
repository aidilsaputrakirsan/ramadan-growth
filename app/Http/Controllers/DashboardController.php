<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function __construct(
        protected \App\Services\PerfectDayCalculator $calculator,
        protected \App\Services\StreakService $streakService,
        protected \App\Services\HijriDateService $hijriService
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

        // Get date info with Hijri conversion
        $dateInfo = $this->hijriService->getFullDateInfo(Carbon::parse($date));

        // Get current Hijri month calendar for heatmap
        $monthCalendar = $this->hijriService->getCurrentMonthCalendar();

        // Get all daily logs for the current Hijri month for heatmap
        $monthDates = collect($monthCalendar['days'])->pluck('gregorian_date')->toArray();
        $dailyLogs = \App\Models\DailyLog::forUser($user->id)
            ->whereIn('date', $monthDates)
            ->get()
            ->keyBy(fn($log) => $log->date->format('Y-m-d'));

        // Merge calendar with log data
        $heatmapData = collect($monthCalendar['days'])->map(function ($day) use ($dailyLogs) {
            $log = $dailyLogs->get($day['gregorian_date']);
            return array_merge($day, [
                'has_log' => $log !== null,
                'is_perfect' => $log?->is_perfect_day ?? false,
                'tasks_completed' => $log?->tasks_completed ?? [],
                'completion_count' => $log ? count(array_filter($log->tasks_completed ?? [])) : 0,
            ]);
        });

        // Calculate sunnah stats for radar chart
        $sunnahKeys = ['tarawih', 'tilawah_quran', 'sedekah', 'tahajud', 'dhuha', 'dzikir_pagi_petang'];
        $sunnahStats = [];
        foreach ($sunnahKeys as $key) {
            $sunnahStats[$key] = $dailyLogs->filter(function ($log) use ($key) {
                return ($log->tasks_completed[$key] ?? false) === true;
            })->count();
        }

        return \Inertia\Inertia::render('Dashboard', [
            'todayLog' => $todayLog,
            'totalPerfectDays' => $totalPerfectDays,
            'currentStreak' => $currentStreak,
            'masjidStage' => $masjidStage,
            'selectedDate' => $date,
            'dateInfo' => $dateInfo,
            'heatmapData' => $heatmapData,
            'hijriMonthName' => $monthCalendar['hijri_month_name'],
            'hijriYear' => $monthCalendar['hijri_year'],
            'sunnahStats' => $sunnahStats,
            'wajibStats' => [
                'shalat_5_waktu' => $dailyLogs->filter(fn($log) => ($log->tasks_completed['shalat_5_waktu'] ?? false) === true)->count(),
                'puasa' => $dailyLogs->filter(fn($log) => ($log->tasks_completed['puasa'] ?? false) === true)->count(),
            ],
            'totalMonthDays' => $dailyLogs->count(),
        ]);
    }
}
