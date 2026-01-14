<?php

namespace App\Services;

class IbadahService
{
    /**
     * Ibadah yang Wajib (Foundation)
     */
    public const WAJIB = [
        'shalat_5_waktu' => [
            'label' => 'Shalat 5 Waktu',
            'icon' => '🕌',
            'desc' => 'Subuh, Dzuhur, Ashar, Maghrib, Isya'
        ],
        'puasa' => [
            'label' => 'Puasa',
            'icon' => '🥤',
            'desc' => 'Menahan diri dari fajar hingga maghrib'
        ],
    ];

    /**
     * Ibadah Sunnah (Growth & Decoration)
     */
    public const SUNNAH = [
        'tarawih' => [
            'label' => 'Shalat Tarawih',
            'icon' => '🌙',
            'desc' => 'Qiyamul Lail berjamaah/sendiri'
        ],
        'tilawah_quran' => [
            'label' => 'Tilawah Qur\'an',
            'icon' => '📖',
            'desc' => 'Minimal 1 halaman atau 1 juz'
        ],
        'sedekah' => [
            'label' => 'Sedekah',
            'icon' => '🤲',
            'desc' => 'Berbagi rezeki atau kebaikan'
        ],
        'tahajud' => [
            'label' => 'Shalat Tahajud',
            'icon' => '🌌',
            'desc' => 'Shalat malam setelah tidur'
        ],
        'dhuha' => [
            'label' => 'Shalat Dhuha',
            'icon' => '☀️',
            'desc' => 'Shalat di waktu pagi'
        ],
        'dzikir_pagi_petang' => [
            'label' => 'Dzikir Pagi/Petang',
            'icon' => '📿',
            'desc' => 'Al-Ma\'tsurat atau dzikir harian'
        ],
    ];

    /**
     * Get tasks required for a "Perfect Day" (Masjid Building).
     * Currently: Wajib + Top Sunnahs (Tilawah, Sedekah, Tarawih? -> Let's keep original 4 for now + maybe Tarawih)
     * Let's sticking to User's "Syarat sudah dijelaskan" -> The original 4.
     */
    public const REQUIRED_FOR_PERFECT = [
        'shalat_5_waktu',
        'puasa',
        'tilawah_quran',
        'sedekah',
    ];

    public function getWajibList(): array
    {
        return self::WAJIB;
    }

    public function getSunnahList(): array
    {
        return self::SUNNAH;
    }

    public function getRequiredTasks(): array
    {
        return self::REQUIRED_FOR_PERFECT;
    }
}
