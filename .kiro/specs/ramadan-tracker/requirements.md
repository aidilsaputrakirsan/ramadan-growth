# Requirements Document

## Introduction

My Ramadan Growth adalah aplikasi web Personal Ramadan Tracker yang dirancang untuk pengguna Gen Z. Aplikasi ini memvisualisasikan progres ibadah pribadi selama 30 hari Ramadan melalui evolusi visual sebuah Masjid. Dibangun dengan stack modern: Laravel 12, Inertia.js 2.0, dan Vue 3.5+ dengan fokus pada pengalaman mobile-first yang estetik dan responsif.

## Glossary

- **Dashboard**: Halaman utama aplikasi yang menampilkan check-in ibadah dan visualisasi Masjid
- **Daily_Log**: Record harian yang menyimpan status check-in ibadah user
- **Perfect_Day**: Hari di mana user menyelesaikan semua 4 ibadah (Puasa, Shalat 5 Waktu, Tarawih, Tilawah)
- **Masjid_Evolution**: Sistem visual yang menampilkan evolusi bangunan masjid berdasarkan akumulasi Perfect Days
- **Streak**: Jumlah hari berturut-turut user mencapai Perfect Day
- **Check_In_System**: Mekanisme toggle untuk mencatat penyelesaian ibadah harian
- **Stage**: Level evolusi visual Masjid (Stage 1-4)

## Requirements

### Requirement 1: User Authentication

**User Story:** As a user, I want to register and login to the application, so that I can track my personal Ramadan journey securely.

#### Acceptance Criteria

1. WHEN a user visits the application for the first time, THE Dashboard SHALL redirect to the login page
2. WHEN a user submits valid registration data, THE Authentication_System SHALL create a new user account and redirect to Dashboard
3. WHEN a user submits valid login credentials, THE Authentication_System SHALL authenticate the user and redirect to Dashboard
4. WHEN a user clicks logout, THE Authentication_System SHALL terminate the session and redirect to login page
5. IF a user submits invalid credentials, THEN THE Authentication_System SHALL display an appropriate error message

### Requirement 2: Daily Check-in System

**User Story:** As a user, I want to check-in my daily ibadah with one tap, so that I can easily track my worship activities.

#### Acceptance Criteria

1. WHEN a user views the Dashboard, THE Check_In_System SHALL display 4 toggle buttons for: Puasa, Shalat 5 Waktu, Tarawih, and Tilawah
2. WHEN a user taps a toggle button, THE Check_In_System SHALL immediately update the check-in status without page reload
3. WHEN a user completes all 4 ibadah toggles, THE Check_In_System SHALL mark the day as a Perfect_Day
4. WHEN a user checks in for a new date, THE Check_In_System SHALL create a new Daily_Log record
5. WHEN a user modifies check-in on the same date, THE Check_In_System SHALL update the existing Daily_Log record
6. THE Check_In_System SHALL persist all check-in data to the database immediately after each toggle

### Requirement 3: Perfect Days Calculation

**User Story:** As a user, I want the system to automatically calculate my perfect days, so that I can see my overall Ramadan progress.

#### Acceptance Criteria

1. WHEN a Daily_Log has all 4 ibadah completed, THE Calculation_System SHALL set is_perfect_day to true
2. WHEN a Daily_Log has fewer than 4 ibadah completed, THE Calculation_System SHALL set is_perfect_day to false
3. WHEN the Dashboard loads, THE Calculation_System SHALL compute the total count of Perfect Days for the user
4. WHEN a check-in status changes, THE Calculation_System SHALL recalculate the Perfect Day status for that date

### Requirement 4: Streak Counter

**User Story:** As a user, I want to see my consecutive perfect days streak, so that I can stay motivated to maintain my ibadah consistency.

#### Acceptance Criteria

1. WHEN the Dashboard loads, THE Streak_Counter SHALL display the current consecutive Perfect Days count
2. WHEN a user achieves a new Perfect Day that continues the streak, THE Streak_Counter SHALL increment by 1
3. WHEN a user misses a Perfect Day, THE Streak_Counter SHALL reset to 0
4. THE Streak_Counter SHALL only count consecutive days without gaps

### Requirement 5: Masjid Evolution Visualization

**User Story:** As a user, I want to see a visual representation of a mosque that evolves based on my progress, so that I feel motivated by seeing my spiritual growth.

#### Acceptance Criteria

1. WHILE total Perfect Days is between 0-7, THE Masjid_Evolution SHALL display Stage 1 (empty land/foundation)
2. WHILE total Perfect Days is between 8-15, THE Masjid_Evolution SHALL display Stage 2 (building structure rising)
3. WHILE total Perfect Days is between 16-23, THE Masjid_Evolution SHALL display Stage 3 (dome and minaret installed)
4. WHILE total Perfect Days is between 24-30, THE Masjid_Evolution SHALL display Stage 4 (magnificent mosque with lamp decorations and night sky background)
5. WHEN the Masjid stage changes, THE Masjid_Evolution SHALL animate the transition using Vue Transition with fade or scale effect

### Requirement 6: Mobile-First Responsive Dashboard

**User Story:** As a user, I want to access the tracker easily from my phone, so that I can check-in my ibadah anytime anywhere.

#### Acceptance Criteria

1. THE Dashboard SHALL be optimized for mobile viewport (320px - 480px) as primary design
2. THE Dashboard SHALL scale appropriately for tablet (481px - 768px) and desktop (769px+) viewports
3. THE Dashboard SHALL load and respond to interactions within 200ms on mobile devices
4. WHEN a user taps interactive elements, THE Dashboard SHALL provide micro-interaction feedback (bounce effect)

### Requirement 7: SPA Experience with Inertia.js

**User Story:** As a user, I want the application to feel instant without page reloads, so that I have a smooth modern app experience.

#### Acceptance Criteria

1. WHEN navigating between pages, THE Application SHALL not perform full page reloads
2. WHEN submitting forms or toggling check-ins, THE Application SHALL update the UI without page refresh
3. THE Application SHALL preserve scroll position during navigation
4. WHEN data is being loaded, THE Application SHALL display appropriate loading indicators

### Requirement 8: Data Persistence

**User Story:** As a user, I want my check-in data to be saved reliably, so that I don't lose my Ramadan progress.

#### Acceptance Criteria

1. WHEN a check-in is submitted, THE Database SHALL store the Daily_Log with user_id, date, tasks_completed (JSON), and is_perfect_day fields
2. WHEN the Dashboard loads, THE Application SHALL retrieve all Daily_Logs for the authenticated user
3. THE Database SHALL enforce unique constraint on user_id and date combination
4. IF a database error occurs during save, THEN THE Application SHALL display an error message and allow retry
