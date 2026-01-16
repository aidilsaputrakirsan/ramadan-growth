<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CommunityController extends Controller
{
    public function __construct(
        protected \App\Services\PerfectDayCalculator $calculator
    ) {}

    /**
     * Display the community leaderboard.
     */
    public function index(Request $request): \Inertia\Response
    {
        $sunnahKeys = array_keys(\App\Services\IbadahService::SUNNAH);

        $users = \App\Models\User::query()
            ->withCount(['dailyLogs as perfect_days_count' => function ($query) {
                $query->where('is_perfect_day', true);
            }])
            ->with('dailyLogs')
            ->orderByDesc('perfect_days_count')
            ->orderBy('name')
            ->limit(50)
            ->get()
            ->map(function ($user) use ($sunnahKeys) {
                // Hitung total sunnah completed
                $totalSunnah = 0;
                foreach ($user->dailyLogs as $log) {
                    if (is_array($log->tasks_completed)) {
                        foreach ($sunnahKeys as $key) {
                            if (isset($log->tasks_completed[$key]) && $log->tasks_completed[$key] === true) {
                                $totalSunnah++;
                            }
                        }
                    }
                }

                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'avatar' => $user->avatar,
                    'perfect_days_count' => $user->perfect_days_count,
                    'total_sunnah' => $totalSunnah,
                ];
            });

        // Top 5 untuk chart (berdasarkan total sunnah)
        $top5Sunnah = $users->sortByDesc('total_sunnah')->take(5)->values();

        return \Inertia\Inertia::render('Community', [
            'leaderboard' => $users,
            'top5Sunnah' => $top5Sunnah,
        ]);
    }

    /**
     * Get specific user stats (API).
     */
    public function show(\App\Models\User $user)
    {
        $logs = $user->dailyLogs()->get(); 
        $stats = [];
        
        foreach ($logs as $log) {
            // tasks_completed is array casted
            if (is_array($log->tasks_completed)) {
                foreach ($log->tasks_completed as $key => $val) {
                    if ($val === true) {
                         $stats[$key] = ($stats[$key] ?? 0) + 1;
                    }
                }
            }
        }
        
        arsort($stats);

        // Map keys to readable labels - HANYA SUNNAH untuk leaderboard
        $result = [];
        $sunnah = \App\Services\IbadahService::SUNNAH;

        foreach ($stats as $key => $count) {
            // Hanya tampilkan sunnah, skip wajib
            if (isset($sunnah[$key])) {
                $result[] = [
                    'key' => $key,
                    'label' => $sunnah[$key]['label'],
                    'icon' => $sunnah[$key]['icon'],
                    'count' => $count
                ];
            }
        }

        $perfectDays = $user->dailyLogs()->where('is_perfect_day', true)->count();

        return response()->json([
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'avatar' => $user->avatar,
                'perfect_days' => $perfectDays,
            ],
            'stats' => $result
        ]);
    }
}
