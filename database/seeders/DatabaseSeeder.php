<?php

namespace Database\Seeders;

use App\Models\User;
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
     * Generate random avatar URL
     */
    private function generateAvatar(): string
    {
        $style = $this->avatarStyles[array_rand($this->avatarStyles)];
        $seed = $this->avatarSeeds[array_rand($this->avatarSeeds)] . rand(1, 100);
        return "https://api.dicebear.com/7.x/{$style}/svg?seed={$seed}&backgroundColor=transparent";
    }

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create Admin Account
        User::factory()->create([
            'name' => 'Admin Ramadan',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'is_admin' => true,
            'avatar' => 'https://api.dicebear.com/7.x/lorelei/svg?seed=admin&backgroundColor=transparent',
        ]);

        // Nama-nama Islami untuk dummy users
        $islamicNames = [
            'Ahmad Fauzi', 'Fatimah Zahra', 'Muhammad Rizki', 'Aisyah Putri',
            'Abdullah Rahman', 'Khadijah Sari', 'Umar Hakim', 'Maryam Dewi',
            'Ali Akbar', 'Zainab Nur', 'Hasan Basri', 'Ruqayyah Indah',
            'Ibrahim Malik', 'Hafsa Wulan', 'Yusuf Pratama', 'Aminah Lestari',
            'Bilal Saputra', 'Safiya Rahma', 'Khalid Wijaya', 'Asma Cantika',
            'Hamza Putra', 'Sumaya Fitri', 'Idris Nugroho', 'Layla Permata',
        ];

        $users = collect();

        // Create users dengan nama Islami
        foreach ($islamicNames as $index => $name) {
            $user = User::factory()->create([
                'name' => $name,
                'email' => strtolower(str_replace(' ', '.', $name)) . '@example.com',
                'avatar' => $this->generateAvatar(),
            ]);
            $users->push($user);
        }

        $startDate = now()->subDays(30);
        $wajibKeys = array_keys(\App\Services\IbadahService::WAJIB);
        $sunnahKeys = array_keys(\App\Services\IbadahService::SUNNAH);
        $requiredForPerfect = \App\Services\IbadahService::REQUIRED_FOR_PERFECT;

        foreach ($users as $index => $user) {
            // Determine diligence level untuk variasi leaderboard
            $diligenceLevel = match (true) {
                $index < 3 => 95,   // Top 3: sangat rajin (95% perfect days)
                $index < 8 => 80,   // Next 5: rajin (80%)
                $index < 15 => 60,  // Next 7: sedang (60%)
                default => 35,      // Sisanya: jarang (35%)
            };

            // Loop 30 hari terakhir
            for ($day = 0; $day < 30; $day++) {
                $date = $startDate->copy()->addDays($day)->toDateString();
                
                $isPerfect = rand(0, 100) < $diligenceLevel;
                $tasksCompleted = [];
                
                if ($isPerfect) {
                    // Perfect day: semua required = true
                    foreach ($requiredForPerfect as $key) {
                        $tasksCompleted[$key] = true;
                    }
                    // Sunnah lainnya: high chance
                    foreach ($sunnahKeys as $key) {
                        if (!in_array($key, $requiredForPerfect)) {
                            $tasksCompleted[$key] = rand(0, 100) < 75;
                        }
                    }
                } else {
                    // Not perfect: randomize semua
                    foreach (array_merge($wajibKeys, $sunnahKeys) as $key) {
                        $tasksCompleted[$key] = rand(0, 100) < 45;
                    }
                }

                $user->dailyLogs()->create([
                    'date' => $date,
                    'tasks_completed' => $tasksCompleted,
                    'is_perfect_day' => $isPerfect,
                ]);
            }
        }

        $this->command->info('✅ Seeded ' . count($islamicNames) . ' users dengan avatar dan 30 hari log ibadah!');
    }
}
