# Implementation Plan: My Ramadan Growth

## Overview

Implementasi aplikasi Personal Ramadan Tracker dengan Laravel 12, Inertia.js 2.0, dan Vue 3.5+. Tasks disusun secara incremental dari setup hingga polishing, dengan setiap task membangun di atas task sebelumnya.

## Tasks

- [x] 1. Project Setup dengan Laravel Breeze
  - [x] 1.1 Install Laravel 12 fresh project
    - Jalankan `composer create-project laravel/laravel ramadan-tracker`
    - Configure database connection di `.env`
    - _Requirements: 1.1, 1.2, 1.3_
  - [x] 1.2 Install Laravel Breeze dengan Vue + Inertia
    - Jalankan `composer require laravel/breeze --dev`
    - Jalankan `php artisan breeze:install vue --typescript`
    - Jalankan `npm install && npm run build`
    - _Requirements: 1.1, 1.2, 1.3, 7.1, 7.2_
  - [x] 1.3 Verify authentication flow works
    - Test register, login, logout functionality
    - _Requirements: 1.1, 1.2, 1.3, 1.4, 1.5_

- [x] 2. Database Layer
  - [x] 2.1 Create daily_logs migration
    - Create migration dengan fields: id, user_id, date, tasks_completed (JSON), is_perfect_day (boolean)
    - Add foreign key constraint ke users table
    - Add unique constraint pada (user_id, date)
    - _Requirements: 8.1, 8.3_
  - [x] 2.2 Create DailyLog Eloquent Model
    - Define fillable, casts untuk JSON dan boolean
    - Add relationship ke User model
    - Add scopes: forUser(), perfectDays()
    - _Requirements: 8.1, 8.2_
  - [ ]* 2.3 Write property test untuk unique constraint
    - **Property 3: Daily Log Update Idempotence**
    - **Validates: Requirements 2.5, 8.3**

- [x] 3. Business Logic Services
  - [x] 3.1 Create PerfectDayCalculator service
    - Implement isPerfectDay(array $tasksCompleted): bool
    - Implement getTotalPerfectDays(int $userId): int
    - Implement getMasjidStage(int $perfectDays): int
    - _Requirements: 2.3, 3.1, 3.2, 3.3, 5.1, 5.2, 5.3, 5.4_
  - [ ]* 3.2 Write property test untuk Perfect Day calculation
    - **Property 1: Perfect Day Calculation**
    - **Validates: Requirements 2.3, 3.1, 3.2**
  - [ ]* 3.3 Write property test untuk Masjid Stage calculation
    - **Property 5: Masjid Stage Calculation**
    - **Validates: Requirements 5.1, 5.2, 5.3, 5.4**
  - [x] 3.4 Create StreakService
    - Implement getCurrentStreak(int $userId): int
    - Calculate consecutive perfect days ending at current date
    - _Requirements: 4.1, 4.2, 4.3, 4.4_
  - [ ]* 3.5 Write property test untuk Streak calculation
    - **Property 4: Streak Calculation Correctness**
    - **Validates: Requirements 4.1, 4.2, 4.3, 4.4**

- [ ] 4. Checkpoint - Backend Logic Complete
  - Ensure all tests pass, ask the user if questions arise.

- [x] 5. Controllers dan Routes
  - [x] 5.1 Create DailyLogController
    - Implement store() untuk create/update daily log
    - Use Form Request validation untuk tasks_completed
    - Auto-calculate is_perfect_day saat save
    - _Requirements: 2.2, 2.4, 2.5, 2.6, 8.1_
  - [ ]* 5.2 Write property test untuk persistence round-trip
    - **Property 2: Daily Log Persistence Round-Trip**
    - **Validates: Requirements 2.4, 2.6, 8.1**
  - [x] 5.3 Update DashboardController
    - Inject PerfectDayCalculator dan StreakService
    - Pass todayLog, totalPerfectDays, currentStreak, masjidStage ke Inertia
    - _Requirements: 3.3, 4.1, 5.1, 5.2, 5.3, 5.4_
  - [ ]* 5.4 Write property test untuk Total Perfect Days count
    - **Property 6: Total Perfect Days Count**
    - **Validates: Requirements 3.3, 8.2**
  - [x] 5.5 Setup routes
    - POST /daily-log untuk store check-in
    - Dashboard route sudah ada dari Breeze
    - _Requirements: 2.2, 7.1, 7.2_

- [ ] 6. Checkpoint - Backend Complete
  - Ensure all tests pass, ask the user if questions arise.

- [x] 7. Frontend Components
  - [x] 7.1 Create TypeScript interfaces
    - Define DailyLog, TasksCompleted, IbadahType interfaces
    - Extend PageProps untuk Dashboard
    - _Requirements: 2.1_
  - [x] 7.2 Create CheckInToggle.vue component
    - Props: label, modelValue, icon
    - Emit update:modelValue on toggle
    - Add bounce micro-interaction on click
    - Mobile-first styling dengan Tailwind
    - _Requirements: 2.1, 2.2, 6.4_
  - [x] 7.3 Create MasjidEvolution.vue component
    - Props: stage (1-4)
    - Load SVG assets berdasarkan stage
    - Implement Vue Transition untuk stage change animation
    - _Requirements: 5.1, 5.2, 5.3, 5.4, 5.5_
  - [x] 7.4 Create StreakCounter.vue component
    - Props: count
    - Display streak dengan icon dan animasi
    - _Requirements: 4.1_

- [x] 8. Dashboard Integration
  - [x] 8.1 Update Dashboard.vue page
    - Integrate CheckInToggle components untuk 4 ibadah
    - Integrate MasjidEvolution component
    - Integrate StreakCounter component
    - Wire up Inertia form untuk check-in submission
    - _Requirements: 2.1, 2.2, 5.1, 5.2, 5.3, 5.4, 4.1_
  - [ ] 8.2 Implement check-in form handling
    - Use useForm dari @inertiajs/vue3
    - Submit on each toggle change
    - Handle loading dan error states
    - _Requirements: 2.2, 2.6, 7.2, 7.4_
  - [ ] 8.3 Mobile-first responsive styling
    - Optimize layout untuk 320px-480px viewport
    - Add tablet dan desktop breakpoints
    - _Requirements: 6.1, 6.2_

- [ ] 9. Checkpoint - Core Features Complete
  - Ensure all tests pass, ask the user if questions arise.

- [ ] 10. Assets dan Polishing
  - [ ] 10.1 Add Masjid SVG assets
    - Stage 1: Empty land/foundation
    - Stage 2: Building structure
    - Stage 3: Dome and minaret
    - Stage 4: Complete mosque with decorations
    - _Requirements: 5.1, 5.2, 5.3, 5.4_
  - [ ] 10.2 Add micro-interactions
    - Button bounce effect on tap
    - Smooth transitions antar state
    - Loading indicators
    - _Requirements: 6.4, 7.4_
  - [ ] 10.3 Final UI polish
    - Consistent color scheme (Ramadan theme)
    - Typography dan spacing optimization
    - Dark mode support (optional)
    - _Requirements: 6.1, 6.2_

- [x] 11. Community Features (Leaderboard)
  - [x] 11.1 Backend Implementation
    - Create `CommunityController`
    - Implement `index` to fetch top 50 users sorted by perfect days
    - Add `perfect_days_count` using `withCount` on User model
  - [x] 11.2 Frontend Implementation
    - Create `Community.vue` page
    - Create `LeaderboardRow.vue` component
    - Add "Leaderboard" link to Navigation
    - Display user rank, name, masjid stage icon, and total perfect days

- [x] 12. Final Checkpoint
  - Ensure all tests pass, ask the user if questions arise.
  - Verify semua requirements terpenuhi
  - Test end-to-end flow di mobile viewport

- [-] 13. Expanded Ibadah List (Wajib & Sunnah)
  - [/] 13.1 Update Data Structure
    - Create `IbadahService` to define list:
      - **Wajib**: Shalat 5 Waktu, Puasa.
      - **Sunnah**: Tarawih, Tahajud, Dhuha, Witir, Tilawah (Juz/Halaman), Sedekah, Dzikir.
    - Update `PerfectDayCalculator` logic (Determine if "Perfect Day" logic changes).
  - [ ] 13.2 Update Seeder
    - Simulate data for new extended list.
  - [ ] 13.3 Update Dashboard UI
    - Group CheckInToggle by Category (Wajib vs Sunnah).
    - Update icons and labels.

- [x] 13. Expanded Ibadah List (Wajib & Sunnah)
  - [x] 13.1 Update Data Structure
    - Create `IbadahService` to define list:
      - **Wajib**: Shalat 5 Waktu, Puasa.
      - **Sunnah**: Tarawih, Tahajud, Dhuha, Witir, Tilawah (Juz/Halaman), Sedekah, Dzikir.
    - Update `PerfectDayCalculator` logic (Determine if "Perfect Day" logic changes).
  - [x] 13.2 Update Seeder
    - Simulate data for new extended list.
  - [x] 13.3 Update Dashboard UI
    - Group CheckInToggle by Category (Wajib vs Sunnah).
    - Update icons and labels.

- [x] 14. Community Details (User Stats)
  - [x] 14.1 Backend - User Details API
    - Endpoint to fetch specific user's breakdown (e.g. Total Tarawih count, Total Sedekah count).
  - [x] 14.2 Frontend - Detail Modal
    - Click on Leaderboard row opens a Modal/Dialog.
    - Show "Radar/Bar Chart" or simple List: "Strong Points".
    - Example: "Ahli Tarawih 🌙", "Rajin Sedekah 🤲".

- [x] 15. Final Polish & Checkpoint
  - Test new flow with expanded data.

## Notes

- Tasks marked with `*` are optional and can be skipped for faster MVP
- Setiap task references specific requirements untuk traceability
- Property tests validate universal correctness properties
- Unit tests validate specific examples dan edge cases
- Checkpoints memastikan incremental validation
