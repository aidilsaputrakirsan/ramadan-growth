<?php

namespace App\Exports;

use App\Models\DailyLog;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class DailyLogsExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    private $lastUserId = null;
    private $hijriService;

    public function __construct()
    {
        $this->hijriService = new \App\Services\HijriDateService();
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return DailyLog::with('user')
            ->orderBy('user_id', 'asc')
            ->orderBy('date', 'desc')
            ->get();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'ID User',
            'Nama User',
            'Email',
            'Tanggal Masehi',
            'Tanggal Hijriah',
            'Shalat 5 Waktu',
            'Puasa',
            'Tarawih',
            'Tilawah Quran',
            'Sedekah',
            'Tahajud',
            'Dhuha',
            'Dzikir Pagi Petang',
            'Perfect Day',
        ];
    }

    /**
     * @param mixed $log
     * @return array
     */
    public function map($log): array
    {
        $showUser = $this->lastUserId !== $log->user_id;
        $this->lastUserId = $log->user_id;

        $hijriInfo = $this->hijriService->toHijri($log->date);

        return [
            $showUser ? $log->user_id : '',
            $showUser ? ($log->user->name ?? 'N/A') : '',
            $showUser ? ($log->user->email ?? 'N/A') : '',
            $log->date->format('d/m/Y'),
            $hijriInfo['day'] . ' ' . $hijriInfo['month_name'] . ' ' . $hijriInfo['year'],
            $this->formatStatus($log->tasks_completed['shalat_5_waktu'] ?? false),
            $this->formatStatus($log->tasks_completed['puasa'] ?? false),
            $this->formatStatus($log->tasks_completed['tarawih'] ?? false),
            $this->formatStatus($log->tasks_completed['tilawah_quran'] ?? false),
            $this->formatStatus($log->tasks_completed['sedekah'] ?? false),
            $this->formatStatus($log->tasks_completed['tahajud'] ?? false),
            $this->formatStatus($log->tasks_completed['dhuha'] ?? false),
            $this->formatStatus($log->tasks_completed['dzikir_pagi_petang'] ?? false),
            $log->is_perfect_day ? '🌟 YES' : 'NO',
        ];
    }

    /**
     * @param Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet)
    {
        // Get total rows and columns
        $highestRow = $sheet->getHighestRow();
        $highestColumn = $sheet->getHighestColumn();
        $range = 'A1:' . $highestColumn . $highestRow;

        // Alignment for all cells
        $sheet->getStyle($range)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle($range)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

        return [
            // Style the first row as header
            1 => [
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => 'FFFFFF'],
                    'size' => 12,
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '4B39EF'], // Indigo color matching theme
                ],
            ],
        ];
    }

    /**
     * Helper to format true/false into symbols
     */
    private function formatStatus($status): string
    {
        return $status === true ? '✅' : '❌';
    }
}
