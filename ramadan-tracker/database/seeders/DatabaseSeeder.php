<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

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
        ]);

        // Create 20 Dummy Users with varied progress
        $users = User::factory(20)->create();

        $startDate = now()->subDays(30);
        $wajibKeys = array_keys(\App\Services\IbadahService::WAJIB);
        $sunnahKeys = array_keys(\App\Services\IbadahService::SUNNAH);
        
        // Items required for 'perfect' calculation
        $requiredForPerfect = \App\Services\IbadahService::REQUIRED_FOR_PERFECT;

        foreach ($users as $index => $user) {
            // Determine "Type" of user based on index to ensure leaderboard variety
            // Top tier (Rajin), Mid tier (Sedang), Low tier (Jarang)
            $diligenceLevel = match (true) {
                $index < 3 => 95, // Top 3 users: 95% perfect days
                $index < 10 => 70, // Next 7 users: 70% perfect days
                default => 30, // Rest: 30% perfect days
            };

            // Loop through last 30 days
            for ($day = 0; $day < 30; $day++) {
                $date = $startDate->copy()->addDays($day)->toDateString();
                
                // Chance to be perfect day based on diligence
                $isPerfect = rand(0, 100) < $diligenceLevel;

                $tasksCompleted = [];
                
                // Set Wajib & Sunnah
                // If perfect, all REQUIRED are true. Others random high chance.
                // If not perfect, some REQUIRED are false.
                
                if ($isPerfect) {
                    foreach ($requiredForPerfect as $key) {
                        $tasksCompleted[$key] = true;
                    }
                    // Randomize others (sunnahs not in required)
                    foreach ($sunnahKeys as $key) {
                        if (!in_array($key, $requiredForPerfect)) {
                             $tasksCompleted[$key] = rand(0, 100) < 80; // High chance for extra sunnah if diligent
                        }
                    }
                } else {
                    // Randomize all
                    foreach (array_merge($wajibKeys, $sunnahKeys) as $key) {
                        $tasksCompleted[$key] = rand(0, 100) < 50; 
                    }
                    // Ensure at least one required is missing if logic says strictly not perfect?
                    // But isPerfect_day in DB relies on the boolean passed, usually app recalculates.
                    // For safety, let's trust the flag passed to DB.
                }

                $user->dailyLogs()->create([
                    'date' => $date,
                    'tasks_completed' => $tasksCompleted,
                    'is_perfect_day' => $isPerfect, 
                ]);
            }
        }
    }
}
