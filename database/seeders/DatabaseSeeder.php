<?php

namespace Database\Seeders;

use App\Models\User;
use App\Services\HijriDateService;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Avatar styles untuk random assignment
     */
    private array $avatarStyles = [
        'lorelei',
        'lorelei-neutral',
        'notionists',
        'notionists-neutral',
        'micah',
        'miniavs',
        'bottts',
        'bottts-neutral',
        'fun-emoji',
        'thumbs',
        'shapes',
        'glass',
        'rings',
        'pixel-art',
        'pixel-art-neutral',
        'identicon',
    ];

    /**
     * Seeds untuk variasi avatar
     */
    private array $avatarSeeds = [
        'happy', 'cool', 'smile', 'star', 'moon', 'sun', 'love', 'peace',
        'ramadan', 'eid', 'blessed', 'faith', 'hope', 'joy', 'light', 'grace',
    ];

    /**
     * Sunnah keys untuk spider chart
     */
    private array $sunnahKeys = [
        'tarawih',
        'tilawah_quran',
        'sedekah',
        'tahajud',
        'dhuha',
        'dzikir_pagi_petang',
    ];

    /**
     * Generate random avatar URL
     */
    private function generateAvatar(): string
    {
        $style = $this->avatarStyles[array_rand($this->avatarStyles)];
        $seed = $this->avatarSeeds[array_rand($this->avatarSeeds)] . rand(1, 100);
        return "https://api.dicebear.com/7.x/{$style}/svg?seed={$seed}&backgroundColor=transparent";
    }

    /**
     * Generate sunnah strength profile untuk setiap user
     * Setiap user punya kekuatan berbeda di ibadah sunnah tertentu
     */
    private function generateSunnahProfile(): array
    {
        $profiles = [
            // Tipe: Qiyamul Lail (kuat tarawih & tahajud)
            ['tarawih' => 90, 'tilawah_quran' => 60, 'sedekah' => 50, 'tahajud' => 85, 'dhuha' => 40, 'dzikir_pagi_petang' => 55],
            // Tipe: Penghafal (kuat tilawah & dzikir)
            ['tarawih' => 70, 'tilawah_quran' => 95, 'sedekah' => 45, 'tahajud' => 50, 'dhuha' => 55, 'dzikir_pagi_petang' => 90],
            // Tipe: Dermawan (kuat sedekah)
            ['tarawih' => 75, 'tilawah_quran' => 55, 'sedekah' => 95, 'tahajud' => 40, 'dhuha' => 60, 'dzikir_pagi_petang' => 65],
            // Tipe: Morning Person (kuat dhuha & dzikir pagi)
            ['tarawih' => 60, 'tilawah_quran' => 70, 'sedekah' => 55, 'tahajud' => 45, 'dhuha' => 95, 'dzikir_pagi_petang' => 90],
            // Tipe: Night Owl (kuat tahajud & tarawih)
            ['tarawih' => 95, 'tilawah_quran' => 50, 'sedekah' => 40, 'tahajud' => 90, 'dhuha' => 30, 'dzikir_pagi_petang' => 55],
            // Tipe: Balanced (seimbang semua)
            ['tarawih' => 70, 'tilawah_quran' => 70, 'sedekah' => 70, 'tahajud' => 70, 'dhuha' => 70, 'dzikir_pagi_petang' => 70],
            // Tipe: Tilawah Focus
            ['tarawih' => 65, 'tilawah_quran' => 98, 'sedekah' => 50, 'tahajud' => 55, 'dhuha' => 45, 'dzikir_pagi_petang' => 75],
            // Tipe: Social (sedekah & dzikir)
            ['tarawih' => 70, 'tilawah_quran' => 60, 'sedekah' => 92, 'tahajud' => 45, 'dhuha' => 55, 'dzikir_pagi_petang' => 88],
        ];

        return $profiles[array_rand($profiles)];
    }

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $hijriService = new HijriDateService();

        // Get current Hijri month calendar
        $monthCalendar = $hijriService->getCurrentMonthCalendar();
        $monthDates = collect($monthCalendar['days']);

        // Filter hanya tanggal yang sudah lewat atau hari ini
        $availableDates = $monthDates->filter(function ($day) {
            return !$day['is_future'];
        });

        // Jika tidak ada tanggal yang tersedia, gunakan semua hari sebagai simulasi
        if ($availableDates->isEmpty()) {
            $this->command->info('📅 Menggunakan data simulasi untuk bulan ' . $monthCalendar['hijri_month_name'] . '...');
            $availableDates = $monthDates;
        }

        // Create Admin Account
        $admin = User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'is_admin' => true,
            'avatar' => 'https://api.dicebear.com/7.x/lorelei/svg?seed=admin&backgroundColor=transparent',
        ]);

        // 2 user biasa
        $regularUsers = [
            ['name' => 'Ahmad Fauzi', 'email' => 'ahmad@example.com'],
            ['name' => 'Fatimah Zahra', 'email' => 'fatimah@example.com'],
        ];

        $users = collect([$admin]);
        $wajibKeys = array_keys(\App\Services\IbadahService::WAJIB);
        $requiredForPerfect = \App\Services\IbadahService::REQUIRED_FOR_PERFECT;

        // Create regular users
        foreach ($regularUsers as $userData) {
            $user = User::factory()->create([
                'name' => $userData['name'],
                'email' => $userData['email'],
                'avatar' => $this->generateAvatar(),
            ]);
            $users->push($user);
        }

        foreach ($users as $index => $user) {
            // Determine diligence level untuk variasi (perfect day chance)
            $diligenceLevel = match ($index) {
                0 => 90,  // Admin: sangat rajin
                1 => 75,  // User 1: rajin
                2 => 60,  // User 2: sedang
                default => 50,
            };

            // Generate unique sunnah profile untuk user ini
            $sunnahProfile = $this->generateSunnahProfile();

            // Loop semua tanggal bulan ini yang tersedia
            foreach ($availableDates as $day) {
                $date = $day['gregorian_date'];

                $isPerfect = rand(0, 100) < $diligenceLevel;
                $tasksCompleted = [];

                // Wajib tasks
                foreach ($wajibKeys as $key) {
                    if ($isPerfect) {
                        $tasksCompleted[$key] = true;
                    } else {
                        // Tidak perfect: 50-70% chance untuk wajib
                        $tasksCompleted[$key] = rand(0, 100) < 60;
                    }
                }

                // Sunnah tasks berdasarkan profile user
                foreach ($this->sunnahKeys as $key) {
                    // Base chance dari profile + sedikit variasi harian
                    $baseChance = $sunnahProfile[$key] ?? 50;
                    $dailyVariation = rand(-15, 15);
                    $finalChance = max(10, min(98, $baseChance + $dailyVariation));

                    // Jika perfect day, tingkatkan chance sunnah
                    if ($isPerfect) {
                        $finalChance = min(98, $finalChance + 15);
                    }

                    $tasksCompleted[$key] = rand(0, 100) < $finalChance;
                }

                // Recalculate isPerfect berdasarkan actual wajib completion
                $actualPerfect = true;
                foreach ($requiredForPerfect as $key) {
                    if (!($tasksCompleted[$key] ?? false)) {
                        $actualPerfect = false;
                        break;
                    }
                }

                $user->dailyLogs()->create([
                    'date' => $date,
                    'tasks_completed' => $tasksCompleted,
                    'is_perfect_day' => $actualPerfect,
                ]);
            }
        }

        $this->command->info('✅ Seeded ' . $users->count() . ' users dengan data ' . $monthCalendar['hijri_month_name'] . ' ' . $monthCalendar['hijri_year'] . ' H!');
        $this->command->info('📊 ' . $availableDates->count() . ' hari data per user');
    }
}
