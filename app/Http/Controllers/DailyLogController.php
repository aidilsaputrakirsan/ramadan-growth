<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DailyLogController extends Controller
{
    public function __construct(
        protected \App\Services\PerfectDayCalculator $calculator
    ) {}

    /**
     * Store or update a daily log.
     */
    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'tasks_completed' => 'required|array',
            'tasks_completed.*' => 'boolean',
        ]);

        $user = $request->user();
        $tasksCompleted = $validated['tasks_completed'];
        $isPerfectDay = $this->calculator->isPerfectDay($tasksCompleted);

        \App\Models\DailyLog::updateOrCreate(
            [
                'user_id' => $user->id,
                'date' => $validated['date'],
            ],
            [
                'tasks_completed' => $tasksCompleted,
                'is_perfect_day' => $isPerfectDay,
            ]
        );

        return back();
    }
}
