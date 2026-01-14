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
        $users = \App\Models\User::query()
            ->withCount(['dailyLogs as perfect_days_count' => function ($query) {
                $query->where('is_perfect_day', true);
            }])
            ->orderByDesc('perfect_days_count')
            ->orderBy('name')
            ->limit(50)
            ->get()
            ->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'perfect_days_count' => $user->perfect_days_count,
                    'masjid_stage' => $this->calculator->getMasjidStage($user->perfect_days_count),
                ];
            });

        return \Inertia\Inertia::render('Community', [
            'leaderboard' => $users,
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
        
        // Map keys to readable labels
        $result = [];
        $wajib = \App\Services\IbadahService::WAJIB;
        $sunnah = \App\Services\IbadahService::SUNNAH;
        $allDefinitions = array_merge($wajib, $sunnah);

        foreach ($stats as $key => $count) {
             if (isset($allDefinitions[$key])) {
                 $result[] = [
                     'key' => $key,
                     'label' => $allDefinitions[$key]['label'],
                     'icon' => $allDefinitions[$key]['icon'],
                     'count' => $count
                 ];
             }
        }

        $perfectDays = $user->dailyLogs()->where('is_perfect_day', true)->count();

        return response()->json([
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'perfect_days' => $perfectDays,
                'masjid_stage' => $this->calculator->getMasjidStage($perfectDays)
            ],
            'stats' => $result
        ]);
    }
}
