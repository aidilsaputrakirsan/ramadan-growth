export interface User {
    id: number;
    name: string;
    email: string;
    email_verified_at?: string;
    is_admin?: boolean;
}

export type IbadahType = 'shalat_5_waktu' | 'tilawah_quran' | 'puasa' | 'sedekah';

export interface TasksCompleted {
    [key: string]: boolean;
}

export interface DailyLog {
    id: number;
    user_id: number;
    date: string;
    tasks_completed: TasksCompleted;
    is_perfect_day: boolean;
    created_at: string;
    updated_at: string;
}

export interface LeaderboardUser {
    id: number;
    name: string;
    perfect_days_count: number;
    total_sunnah: number;
}

export type PageProps<
    T extends Record<string, unknown> = Record<string, unknown>,
> = T & {
    auth: {
        user: User;
    };
    todayLog?: DailyLog | null;
    totalPerfectDays?: number;
    currentStreak?: number;
    masjidStage?: number;
};
