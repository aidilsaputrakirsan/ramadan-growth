<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Indexes improve query performance significantly:
     * - Without index: MySQL scans ALL rows to find matching data
     * - With index: MySQL jumps directly to matching rows (like book index)
     *
     * These indexes optimize the most common queries in the app:
     * 1. Streak calculation: WHERE user_id = ? AND is_perfect_day = true
     * 2. Date range queries: WHERE user_id = ? AND date BETWEEN ? AND ?
     */
    public function up(): void
    {
        Schema::table('daily_logs', function (Blueprint $table) {
            // Index for streak and perfect day queries
            // Used by: StreakService, PerfectDayCalculator
            $table->index(['user_id', 'is_perfect_day', 'date'], 'daily_logs_streak_index');

            // Index for date-based lookups (heatmap, calendar)
            // Used by: DashboardController heatmap queries
            $table->index(['user_id', 'date'], 'daily_logs_user_date_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('daily_logs', function (Blueprint $table) {
            $table->dropIndex('daily_logs_streak_index');
            $table->dropIndex('daily_logs_user_date_index');
        });
    }
};
