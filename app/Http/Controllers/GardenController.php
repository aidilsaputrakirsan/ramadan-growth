<?php

namespace App\Http\Controllers;

use App\Models\DailyLog;
use App\Services\HijriDateService;
use App\Services\PerfectDayCalculator;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class GardenController extends Controller
{
    public function __construct(
        protected PerfectDayCalculator $calculator,
        protected HijriDateService $hijriService
    ) {
    }

    public function index(Request $request): Response
    {
        $userId = $request->user()->id;
        $monthCalendar = $this->hijriService->getCurrentMonthCalendar();
        
        // Count perfect days in current month
        $monthDates = collect($monthCalendar['days'])->pluck('gregorian_date')->toArray();
        $perfectDaysCount = DailyLog::forUser($userId)
            ->whereIn('date', $monthDates)
            ->where('is_perfect_day', true)
            ->count();

        return Inertia::render('Garden', [
            'perfectDays' => $perfectDaysCount,
            'totalMonthDays' => count($monthCalendar['days']),
            'hijriMonthName' => $monthCalendar['hijri_month_name'],
            'hijriYear' => $monthCalendar['hijri_year'],
        ]);
    }
}
