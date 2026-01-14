# Design Document: My Ramadan Growth

## Overview

My Ramadan Growth adalah aplikasi web Personal Ramadan Tracker yang memvisualisasikan progres ibadah melalui evolusi visual Masjid. Aplikasi dibangun dengan arsitektur modern menggunakan Laravel 12 sebagai backend, Vue 3.5+ dengan Composition API untuk frontend, dan Inertia.js 2.0 sebagai bridge untuk pengalaman SPA yang mulus.

Arsitektur mengikuti pola MVC Laravel dengan Inertia.js yang menghubungkan backend controllers langsung ke Vue components tanpa memerlukan API endpoints terpisah.

## Architecture

```
┌─────────────────────────────────────────────────────────────────┐
│                        Browser (Client)                          │
├─────────────────────────────────────────────────────────────────┤
│  Vue 3.5+ Components (Composition API + <script setup>)         │
│  ┌─────────────┐  ┌─────────────┐  ┌─────────────────────────┐  │
│  │ Auth Pages  │  │  Dashboard  │  │  MasjidEvolution        │  │
│  │ - Login     │  │  - CheckIn  │  │  - Stage1-4 SVG Assets  │  │
│  │ - Register  │  │  - Streak   │  │  - Vue Transitions      │  │
│  └─────────────┘  └─────────────┘  └─────────────────────────┘  │
│                           │                                      │
│                    Inertia.js 2.0                               │
│              (SPA Navigation + Form Handling)                    │
└─────────────────────────────────────────────────────────────────┘
                            │
                            ▼
┌─────────────────────────────────────────────────────────────────┐
│                     Laravel 12 Backend                           │
├─────────────────────────────────────────────────────────────────┤
│  ┌─────────────────┐  ┌─────────────────┐  ┌─────────────────┐  │
│  │ DashboardCtrl   │  │ DailyLogCtrl    │  │ Auth (Breeze)   │  │
│  │ - index()       │  │ - store()       │  │ - login         │  │
│  │ - getStats()    │  │ - update()      │  │ - register      │  │
│  └─────────────────┘  └─────────────────┘  └─────────────────┘  │
│                           │                                      │
│  ┌─────────────────────────────────────────────────────────────┐│
│  │                    Services Layer                            ││
│  │  ┌─────────────────┐  ┌─────────────────────────────────┐   ││
│  │  │ StreakService   │  │ PerfectDayCalculator            │   ││
│  │  │ - calculate()   │  │ - isPerfectDay()                │   ││
│  │  │ - getCurrentStreak│ │ - getTotalPerfectDays()        │   ││
│  │  └─────────────────┘  └─────────────────────────────────┘   ││
│  └─────────────────────────────────────────────────────────────┘│
│                           │                                      │
│  ┌─────────────────────────────────────────────────────────────┐│
│  │                    Eloquent Models                           ││
│  │  ┌─────────────────┐  ┌─────────────────────────────────┐   ││
│  │  │ User            │  │ DailyLog                        │   ││
│  │  │ - id            │  │ - id, user_id, date             │   ││
│  │  │ - name, email   │  │ - tasks_completed (JSON)        │   ││
│  │  │ - password      │  │ - is_perfect_day (boolean)      │   ││
│  │  └─────────────────┘  └─────────────────────────────────┘   ││
│  └─────────────────────────────────────────────────────────────┘│
└─────────────────────────────────────────────────────────────────┘
                            │
                            ▼
┌─────────────────────────────────────────────────────────────────┐
│                    MySQL/SQLite Database                         │
│  ┌─────────────┐  ┌─────────────────────────────────────────┐   │
│  │ users       │  │ daily_logs                              │   │
│  │             │◄─┤ - user_id (FK)                          │   │
│  │             │  │ - UNIQUE(user_id, date)                 │   │
│  └─────────────┘  └─────────────────────────────────────────┘   │
└─────────────────────────────────────────────────────────────────┘
```

## Components and Interfaces

### Backend Components

#### 1. DailyLog Model
```php
// app/Models/DailyLog.php
class DailyLog extends Model
{
    protected $fillable = ['user_id', 'date', 'tasks_completed', 'is_perfect_day'];
    
    protected $casts = [
        'date' => 'date',
        'tasks_completed' => 'array',
        'is_perfect_day' => 'boolean',
    ];
    
    public function user(): BelongsTo;
    public function scopeForUser(Builder $query, int $userId): Builder;
    public function scopePerfectDays(Builder $query): Builder;
}
```

#### 2. DailyLogController
```php
// app/Http/Controllers/DailyLogController.php
class DailyLogController extends Controller
{
    public function store(Request $request): RedirectResponse;
    public function update(Request $request, DailyLog $dailyLog): RedirectResponse;
}
```

#### 3. DashboardController
```php
// app/Http/Controllers/DashboardController.php
class DashboardController extends Controller
{
    public function index(): Response;  // Returns Inertia response with stats
}
```

#### 4. PerfectDayCalculator Service
```php
// app/Services/PerfectDayCalculator.php
class PerfectDayCalculator
{
    public function isPerfectDay(array $tasksCompleted): bool;
    public function getTotalPerfectDays(int $userId): int;
    public function getMasjidStage(int $perfectDays): int;
}
```

#### 5. StreakService
```php
// app/Services/StreakService.php
class StreakService
{
    public function getCurrentStreak(int $userId): int;
}
```

### Frontend Components

#### 1. Dashboard.vue (Main Page)
```typescript
// resources/js/Pages/Dashboard.vue
interface DashboardProps {
    todayLog: DailyLog | null;
    totalPerfectDays: number;
    currentStreak: number;
    masjidStage: number;
}
```

#### 2. CheckInToggle.vue (Reusable Toggle Component)
```typescript
// resources/js/Components/CheckInToggle.vue
interface CheckInToggleProps {
    label: string;
    modelValue: boolean;
    icon: string;
}

interface CheckInToggleEmits {
    (e: 'update:modelValue', value: boolean): void;
}
```

#### 3. MasjidEvolution.vue (Visual Component)
```typescript
// resources/js/Components/MasjidEvolution.vue
interface MasjidEvolutionProps {
    stage: number;  // 1-4
}
```

#### 4. StreakCounter.vue
```typescript
// resources/js/Components/StreakCounter.vue
interface StreakCounterProps {
    count: number;
}
```

### API Contracts (Inertia Props)

#### Dashboard Page Props
```typescript
interface DashboardPageProps {
    auth: {
        user: {
            id: number;
            name: string;
            email: string;
        };
    };
    todayLog: {
        id: number;
        date: string;
        tasks_completed: {
            puasa: boolean;
            shalat: boolean;
            tarawih: boolean;
            tilawah: boolean;
        };
        is_perfect_day: boolean;
    } | null;
    totalPerfectDays: number;
    currentStreak: number;
    masjidStage: number;
}
```

## Data Models

### Database Schema

#### daily_logs Table
```sql
CREATE TABLE daily_logs (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    date DATE NOT NULL,
    tasks_completed JSON NOT NULL,
    is_perfect_day BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    UNIQUE KEY unique_user_date (user_id, date)
);
```

#### tasks_completed JSON Structure
```json
{
    "puasa": true,
    "shalat": true,
    "tarawih": false,
    "tilawah": true
}
```

### TypeScript Interfaces

```typescript
// resources/js/types/index.d.ts
interface DailyLog {
    id: number;
    user_id: number;
    date: string;
    tasks_completed: TasksCompleted;
    is_perfect_day: boolean;
    created_at: string;
    updated_at: string;
}

interface TasksCompleted {
    puasa: boolean;
    shalat: boolean;
    tarawih: boolean;
    tilawah: boolean;
}

type IbadahType = 'puasa' | 'shalat' | 'tarawih' | 'tilawah';
```



## Correctness Properties

*A property is a characteristic or behavior that should hold true across all valid executions of a system—essentially, a formal statement about what the system should do. Properties serve as the bridge between human-readable specifications and machine-verifiable correctness guarantees.*

### Property 1: Perfect Day Calculation

*For any* DailyLog with tasks_completed containing exactly 4 boolean values (puasa, shalat, tarawih, tilawah), the is_perfect_day field SHALL be true if and only if all 4 values are true.

**Validates: Requirements 2.3, 3.1, 3.2**

### Property 2: Daily Log Persistence Round-Trip

*For any* valid check-in submission with user_id, date, and tasks_completed, storing to database then retrieving by user_id and date SHALL return an equivalent DailyLog object.

**Validates: Requirements 2.4, 2.6, 8.1**

### Property 3: Daily Log Update Idempotence

*For any* existing DailyLog, updating the same date multiple times SHALL result in exactly one record for that user_id and date combination, with the latest tasks_completed values.

**Validates: Requirements 2.5, 8.3**

### Property 4: Streak Calculation Correctness

*For any* sequence of DailyLogs for a user ordered by date, the streak count SHALL equal the length of the longest suffix of consecutive dates where all entries have is_perfect_day = true, ending at the current date.

**Validates: Requirements 4.1, 4.2, 4.3, 4.4**

### Property 5: Masjid Stage Calculation

*For any* integer perfectDays in range [0, 30], the getMasjidStage function SHALL return:
- Stage 1 when perfectDays is in [0, 7]
- Stage 2 when perfectDays is in [8, 15]
- Stage 3 when perfectDays is in [16, 23]
- Stage 4 when perfectDays is in [24, 30]

**Validates: Requirements 5.1, 5.2, 5.3, 5.4**

### Property 6: Total Perfect Days Count

*For any* user with N DailyLog records, the getTotalPerfectDays function SHALL return the exact count of records where is_perfect_day = true.

**Validates: Requirements 3.3, 8.2**

## Error Handling

### Backend Error Handling

1. **Validation Errors**: Laravel Form Request validation untuk input check-in
   - tasks_completed harus berupa array dengan 4 boolean keys
   - date harus valid dan tidak di masa depan

2. **Database Errors**: 
   - Unique constraint violation → Return existing record dan update
   - Connection errors → Return 500 dengan pesan user-friendly

3. **Authentication Errors**:
   - Unauthenticated → Redirect ke login (handled by Breeze middleware)
   - Unauthorized → Return 403

### Frontend Error Handling

1. **Network Errors**: Tampilkan toast notification dengan opsi retry
2. **Validation Errors**: Tampilkan inline error messages
3. **Loading States**: Tampilkan skeleton/spinner saat data loading

## Testing Strategy

### Unit Tests (PHPUnit)

Unit tests untuk specific examples dan edge cases:

1. **PerfectDayCalculator Tests**
   - Test dengan semua kombinasi true → expect true
   - Test dengan satu false → expect false
   - Test dengan semua false → expect false

2. **StreakService Tests**
   - Test dengan 0 perfect days → expect 0
   - Test dengan gap di tengah → expect streak setelah gap
   - Test dengan consecutive days → expect correct count

3. **MasjidStage Tests**
   - Test boundary values (0, 7, 8, 15, 16, 23, 24, 30)

### Property-Based Tests (Pest + Faker)

Property tests menggunakan Pest PHP dengan data providers untuk generate random inputs:

1. **Perfect Day Property Test**
   - Generate random boolean combinations
   - Verify is_perfect_day matches all-true condition
   - Minimum 100 iterations

2. **Streak Calculation Property Test**
   - Generate random sequences of DailyLogs
   - Verify streak calculation matches expected algorithm
   - Minimum 100 iterations

3. **Masjid Stage Property Test**
   - Generate random integers 0-30
   - Verify stage mapping is correct
   - Minimum 100 iterations

### Frontend Tests (Vitest)

1. **Component Tests**
   - CheckInToggle emits correct events
   - MasjidEvolution renders correct stage asset
   - StreakCounter displays correct number

2. **Integration Tests**
   - Dashboard loads with correct props
   - Check-in toggle updates state correctly

### Test Configuration

```php
// phpunit.xml - Property test iterations
<env name="PROPERTY_TEST_ITERATIONS" value="100"/>
```

```typescript
// vitest.config.ts
export default defineConfig({
    test: {
        environment: 'jsdom',
        coverage: {
            provider: 'v8'
        }
    }
});
```
