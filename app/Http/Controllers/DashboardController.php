<?php

namespace App\Http\Controllers;

use App\Models\DailyLog;
use App\Services\HijriDateService;
use App\Services\PerfectDayCalculator;
use App\Services\StreakService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function __construct(
        protected PerfectDayCalculator $calculator,
        protected StreakService $streakService,
        protected HijriDateService $hijriService
    ) {
    }

    public function index(Request $request): Response
    {
        $userId = $request->user()->id;
        $date = $this->validateDate($request->query('date'));

        // Get current Hijri month calendar (no DB query)
        $monthCalendar = $this->hijriService->getCurrentMonthCalendar();
        $monthDates = collect($monthCalendar['days'])->pluck('gregorian_date')->toArray();

        // OPTIMIZED: Single query for all month logs
        $dailyLogs = DailyLog::forUser($userId)
            ->whereIn('date', $monthDates)
            ->get()
            ->keyBy(fn($log) => $log->date->format('Y-m-d'));

        // Try to get todayLog from collection first (avoid extra query if date is in current month)
        $todayLog = $dailyLogs->get($date) ?? DailyLog::forUser($userId)->where('date', $date)->first();

        // These use indexed queries (fast)
        $totalPerfectDays = $this->calculator->getTotalPerfectDays($userId);
        $currentStreak = $this->streakService->getCurrentStreak($userId);

        return Inertia::render('Dashboard', [
            'todayLog' => $todayLog,
            'totalPerfectDays' => $totalPerfectDays,
            'currentStreak' => $currentStreak,
            'masjidStage' => $this->calculator->getMasjidStage($totalPerfectDays),
            'selectedDate' => $date,
            'dateInfo' => $this->hijriService->getFullDateInfo(Carbon::parse($date)),
            'heatmapData' => $this->buildHeatmapData($monthCalendar['days'], $dailyLogs),
            'hijriMonthName' => $monthCalendar['hijri_month_name'],
            'hijriYear' => $monthCalendar['hijri_year'],
            'sunnahStats' => $this->calculateSunnahStats($dailyLogs),
            'wajibStats' => $this->calculateWajibStats($dailyLogs),
            'totalMonthDays' => count($monthCalendar['days']),
        ]);
    }

    private function validateDate(?string $date): string
    {
        $today = now()->format('Y-m-d');

        if (!$date) {
            return $today;
        }

        try {
            $dateObj = Carbon::parse($date);
            return $dateObj->gt(Carbon::parse($today)) ? $today : $dateObj->format('Y-m-d');
        } catch (\Exception) {
            return $today;
        }
    }

    private function buildHeatmapData(array $days, Collection $dailyLogs): Collection
    {
        return collect($days)->map(function ($day) use ($dailyLogs) {
            $log = $dailyLogs->get($day['gregorian_date']);

            return [
                ...$day,
                'has_log' => $log !== null,
                'is_perfect' => $log?->is_perfect_day ?? false,
                'tasks_completed' => $log?->tasks_completed ?? [],
                'completion_count' => $log ? count(array_filter($log->tasks_completed ?? [])) : 0,
            ];
        });
    }

    private function calculateSunnahStats(Collection $dailyLogs): array
    {
        $sunnahKeys = ['tarawih', 'tilawah_quran', 'sedekah', 'tahajud', 'dhuha', 'dzikir_pagi_petang'];

        return collect($sunnahKeys)->mapWithKeys(fn($key) => [
            $key => $dailyLogs->filter(fn($log) => ($log->tasks_completed[$key] ?? false) === true)->count()
        ])->all();
    }

    private function calculateWajibStats(Collection $dailyLogs): array
    {
        return [
            'shalat_5_waktu' => $dailyLogs->filter(fn($log) => ($log->tasks_completed['shalat_5_waktu'] ?? false) === true)->count(),
            'puasa' => $dailyLogs->filter(fn($log) => ($log->tasks_completed['puasa'] ?? false) === true)->count(),
        ];
    }
}
