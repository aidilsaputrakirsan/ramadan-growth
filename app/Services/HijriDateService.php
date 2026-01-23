<?php

namespace App\Services;

use Carbon\Carbon;
use GeniusTS\HijriDate\Hijri;

class HijriDateService
{
    /**
     * Nama bulan Hijriah dalam bahasa Arab/Indonesia
     */
    public const HIJRI_MONTHS = [
        1 => 'Muharram',
        2 => 'Safar',
        3 => 'Rabiul Awal',
        4 => 'Rabiul Akhir',
        5 => 'Jumadil Awal',
        6 => 'Jumadil Akhir',
        7 => 'Rajab',
        8 => 'Sya\'ban',
        9 => 'Ramadan',
        10 => 'Syawal',
        11 => 'Dzulqa\'dah',
        12 => 'Dzulhijjah',
    ];

    /**
     * Nama hari dalam bahasa Indonesia
     */
    public const DAY_NAMES = [
        'Sunday' => 'Ahad',
        'Monday' => 'Senin',
        'Tuesday' => 'Selasa',
        'Wednesday' => 'Rabu',
        'Thursday' => 'Kamis',
        'Friday' => 'Jumat',
        'Saturday' => 'Sabtu',
    ];

    /**
     * Nama bulan Masehi dalam bahasa Indonesia
     */
    public const GREGORIAN_MONTHS = [
        1 => 'Januari',
        2 => 'Februari',
        3 => 'Maret',
        4 => 'April',
        5 => 'Mei',
        6 => 'Juni',
        7 => 'Juli',
        8 => 'Agustus',
        9 => 'September',
        10 => 'Oktober',
        11 => 'November',
        12 => 'Desember',
    ];

    /**
     * Convert Gregorian date to Hijri
     */
    public function toHijri(Carbon $date): array
    {
        $hijri = Hijri::convertToHijri($date);

        return [
            'day' => (int) $hijri->day,
            'month' => (int) $hijri->month,
            'month_name' => self::HIJRI_MONTHS[$hijri->month] ?? $hijri->month,
            'year' => (int) $hijri->year,
            'formatted' => $hijri->day . ' ' . (self::HIJRI_MONTHS[$hijri->month] ?? $hijri->month) . ' ' . $hijri->year . ' H',
        ];
    }

    /**
     * Get full date info (both Gregorian and Hijri)
     */
    public function getFullDateInfo(Carbon $date): array
    {
        $hijri = $this->toHijri($date);
        $dayName = self::DAY_NAMES[$date->format('l')] ?? $date->format('l');
        $monthName = self::GREGORIAN_MONTHS[$date->month] ?? $date->format('F');

        return [
            'gregorian' => [
                'day' => $date->day,
                'month' => $date->month,
                'month_name' => $monthName,
                'year' => $date->year,
                'day_name' => $dayName,
                'formatted' => $dayName . ', ' . $date->day . ' ' . $monthName . ' ' . $date->year,
            ],
            'hijri' => $hijri,
            'combined' => $dayName . ', ' . $date->day . ' ' . $monthName . ' ' . $date->year . ' / ' . $hijri['formatted'],
        ];
    }

    /**
     * Check if a date is in Ramadan
     */
    public function isRamadan(Carbon $date): bool
    {
        $hijri = $this->toHijri($date);
        return $hijri['month'] === 9;
    }

    /**
     * Get Ramadan day number (1-30) for a given date
     * Returns null if not in Ramadan
     */
    public function getRamadanDay(Carbon $date): ?int
    {
        $hijri = $this->toHijri($date);
        if ($hijri['month'] !== 9) {
            return null;
        }
        return $hijri['day'];
    }

    /**
     * Get current Hijri year
     */
    public function getCurrentHijriYear(): int
    {
        $hijri = $this->toHijri(Carbon::now());
        return (int) $hijri['year'];
    }

    /**
     * Find 1 Ramadan by searching from a reference date
     * This avoids the buggy Hijri->Gregorian conversion
     */
    public function findRamadan1(int $hijriYear): Carbon
    {
        $today = Carbon::now();
        $todayHijri = $this->toHijri($today);

        // If we're in Ramadan, go back to day 1
        if ($todayHijri['month'] === 9 && $todayHijri['year'] === $hijriYear) {
            return $today->copy()->subDays($todayHijri['day'] - 1);
        }

        // If we're before Ramadan this year, search forward
        if ($todayHijri['year'] === $hijriYear && $todayHijri['month'] < 9) {
            $searchDate = $today->copy();
            for ($i = 0; $i < 365; $i++) {
                $searchDate->addDay();
                $hijri = $this->toHijri($searchDate);
                if ($hijri['month'] === 9 && $hijri['day'] === 1 && $hijri['year'] === $hijriYear) {
                    return $searchDate;
                }
            }
        }

        // If we're after Ramadan this year, search backward
        if ($todayHijri['year'] === $hijriYear && $todayHijri['month'] > 9) {
            $searchDate = $today->copy();
            for ($i = 0; $i < 365; $i++) {
                $searchDate->subDay();
                $hijri = $this->toHijri($searchDate);
                if ($hijri['month'] === 9 && $hijri['day'] === 1 && $hijri['year'] === $hijriYear) {
                    return $searchDate;
                }
            }
        }

        // Different year - estimate and search
        $yearDiff = $hijriYear - $todayHijri['year'];
        $estimatedDays = $yearDiff * 354; // Hijri year ~354 days
        $searchDate = $today->copy()->addDays($estimatedDays);

        // Search around the estimate
        for ($offset = -60; $offset <= 60; $offset++) {
            $checkDate = $searchDate->copy()->addDays($offset);
            $hijri = $this->toHijri($checkDate);
            if ($hijri['month'] === 9 && $hijri['day'] === 1 && $hijri['year'] === $hijriYear) {
                return $checkDate;
            }
        }

        // Fallback: return estimate
        return $searchDate;
    }

    /**
     * Get all 30 days of Ramadan for a given Hijri year
     * Returns array of Gregorian dates
     */
    public function getRamadanDates(int $hijriYear): array
    {
        $ramadan1 = $this->findRamadan1($hijriYear);
        $dates = [];

        for ($day = 1; $day <= 30; $day++) {
            $dates[$day] = $ramadan1->copy()->addDays($day - 1);
        }

        return $dates;
    }

    /**
     * Get Ramadan info for heatmap
     * Returns 30 days with their Gregorian equivalents
     */
    public function getRamadanCalendar(?int $hijriYear = null): array
    {
        $hijriYear = $hijriYear ?? $this->getCurrentHijriYear();
        $dates = $this->getRamadanDates($hijriYear);

        $calendar = [];
        foreach ($dates as $day => $gregorianDate) {
            $calendar[] = [
                'ramadan_day' => $day,
                'gregorian_date' => $gregorianDate->format('Y-m-d'),
                'gregorian_formatted' => $gregorianDate->format('d M'),
                'hijri_formatted' => $day . ' Ramadan ' . $hijriYear . ' H',
                'is_today' => $gregorianDate->isToday(),
                'is_past' => $gregorianDate->isPast() && !$gregorianDate->isToday(),
                'is_future' => $gregorianDate->isFuture(),
            ];
        }

        return [
            'hijri_year' => $hijriYear,
            'days' => $calendar,
        ];
    }

    /**
     * Get current Hijri month info
     */
    public function getCurrentHijriMonth(): array
    {
        $hijri = $this->toHijri(Carbon::now());
        return [
            'month' => $hijri['month'],
            'month_name' => $hijri['month_name'],
            'year' => $hijri['year'],
        ];
    }

    /**
     * Find the 1st day of a specific Hijri month
     */
    public function findMonth1(int $hijriMonth, int $hijriYear): Carbon
    {
        $today = Carbon::now();
        $todayHijri = $this->toHijri($today);

        // If we're in the target month, go back to day 1
        if ($todayHijri['month'] === $hijriMonth && $todayHijri['year'] === $hijriYear) {
            return $today->copy()->subDays($todayHijri['day'] - 1);
        }

        // Calculate approximate difference in days
        $monthDiff = ($hijriYear - $todayHijri['year']) * 12 + ($hijriMonth - $todayHijri['month']);
        $estimatedDays = $monthDiff * 29.5; // Hijri month ~29.5 days

        $searchDate = $today->copy()->addDays((int) $estimatedDays);

        // Search around the estimate
        for ($offset = -45; $offset <= 45; $offset++) {
            $checkDate = $searchDate->copy()->addDays($offset);
            $hijri = $this->toHijri($checkDate);
            if ($hijri['month'] === $hijriMonth && $hijri['day'] === 1 && $hijri['year'] === $hijriYear) {
                return $checkDate;
            }
        }

        // Fallback
        return $searchDate;
    }

    /**
     * Get all days of a specific Hijri month
     */
    public function getMonthDates(int $hijriMonth, int $hijriYear): array
    {
        $month1 = $this->findMonth1($hijriMonth, $hijriYear);
        $dates = [];
        $monthName = self::HIJRI_MONTHS[$hijriMonth] ?? 'Unknown';

        // Hijri months have 29 or 30 days
        for ($day = 1; $day <= 30; $day++) {
            $currentDate = $month1->copy()->addDays($day - 1);
            $hijri = $this->toHijri($currentDate);

            // Stop if we've moved to the next month
            if ($hijri['month'] !== $hijriMonth) {
                break;
            }

            $dates[$day] = $currentDate;
        }

        return $dates;
    }

    /**
     * Get current Hijri month calendar for heatmap
     * Returns days with their Gregorian equivalents
     */
    public function getCurrentMonthCalendar(): array
    {
        $currentMonth = $this->getCurrentHijriMonth();
        $hijriMonth = $currentMonth['month'];
        $hijriYear = $currentMonth['year'];
        $monthName = $currentMonth['month_name'];

        $dates = $this->getMonthDates($hijriMonth, $hijriYear);

        $calendar = [];
        foreach ($dates as $day => $gregorianDate) {
            $calendar[] = [
                'hijri_day' => $day,
                'gregorian_date' => $gregorianDate->format('Y-m-d'),
                'gregorian_formatted' => $gregorianDate->format('d M'),
                'hijri_formatted' => $day . ' ' . $monthName . ' ' . $hijriYear . ' H',
                'is_today' => $gregorianDate->isToday(),
                'is_past' => $gregorianDate->isPast() && !$gregorianDate->isToday(),
                'is_future' => $gregorianDate->isFuture(),
            ];
        }

        return [
            'hijri_month' => $hijriMonth,
            'hijri_month_name' => $monthName,
            'hijri_year' => $hijriYear,
            'total_days' => count($calendar),
            'days' => $calendar,
        ];
    }
}
