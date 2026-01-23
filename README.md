# Ramadan Growth

<div align="center">

![Ramadan Growth](https://img.shields.io/badge/Ramadan-Growth-34d399?style=for-the-badge)
![Laravel](https://img.shields.io/badge/Laravel-12-FF2D20?style=for-the-badge&logo=laravel)
![Vue.js](https://img.shields.io/badge/Vue.js-3-4FC08D?style=for-the-badge&logo=vue.js)
![TypeScript](https://img.shields.io/badge/TypeScript-5-3178C6?style=for-the-badge&logo=typescript)

**Aplikasi Tracker Ibadah Ramadan Personal**

Bangun kebiasaan ibadah yang konsisten selama bulan Ramadan dengan tracking harian dan streak counter.

[Instalasi](#-instalasi) • [Panduan](#-panduan-penggunaan) • [Fitur](#-fitur-utama) • [Tech Stack](#-tech-stack)

</div>

---

## Tentang Aplikasi

**Ramadan Growth** adalah aplikasi web untuk membantu Anda membangun kebiasaan ibadah yang baik selama bulan Ramadan. Aplikasi ini **fokus pada personal growth** tanpa elemen kompetisi - murni untuk tracking progress pribadi Anda sendiri.

### Konsep Utama

- **Perfect Day**: Hari sempurna tercapai ketika Anda menyelesaikan semua **ibadah wajib** (Shalat 5 waktu + Puasa)
- **Streak Tracker**: Hitung berapa hari berturut-turut Anda mencapai perfect day
- **Backfill Support**: Isi log ibadah untuk hari-hari yang terlewat
- **Auto-save**: Setiap perubahan tersimpan otomatis

---

## Fitur Utama

### 1. Dashboard dengan Date Navigation
- **Navigasi Tanggal**: Lihat dan isi log ibadah untuk tanggal mana pun
- **Tombol Navigasi**: Mundur/maju per hari dengan tombol previous/next
- **Quick Jump**: Tombol "Kembali ke Hari Ini" untuk navigasi cepat
- **Validasi**: Tidak bisa mengisi tanggal di masa depan

### 2. Checklist Ibadah Harian

**Ibadah Wajib** (Required untuk Perfect Day):
- Shalat 5 Waktu
- Puasa Ramadan

**Ibadah Sunnah** (Opsional):
- Shalat Tarawih
- Tilawah Al-Qur'an
- Sedekah
- Shalat Tahajud
- Shalat Dhuha
- Dzikir Pagi & Petang

### 3. Statistik Personal
- **Profil Wajib (Ring Chart)**: Visualisasi modern untuk konsistensi Shalat & Puasa
- **Profil Sunnah (Radar Chart)**: Analisis kekuatan ibadah sunnah Anda
- **Perfect Days Counter**: Total hari sempurna yang Anda capai
- **Streak Counter**: Berapa hari berturut-turut perfect day
- **Auto-save**: Otomatis menyimpan setiap perubahan

### 4. Dashboard Features
- **Greeting Card**: Sapaan personal dengan Avatar pengguna
- **Date Navigation**: Kontrol penuh untuk melihat riwayat ibadah
- **Gamification**: Level Masjid berkembang seiring jumlah Perfect Days

### 4. Profile Management
- Edit nama dan email
- Pilih avatar dari 10+ pilihan
- Update password
- Delete account

### 5. Admin Panel
- Lihat daftar semua user dengan statistik perfect days
- Edit user (nama/email)
- Reset password user
- Hapus user

### 6. Authentication
- Register akun baru
- Login
- Forgot password dengan email
- Email verification

---

## Tech Stack

### Backend
- **Laravel 12** - PHP Framework
- **PHP 8.2+** - Language
- **MySQL/SQLite** - Database
- **Inertia.js** - Server-side routing dengan SPA experience

### Frontend
- **Vue 3** - Progressive JavaScript Framework
- **TypeScript** - Type-safe JavaScript
- **Tailwind CSS v4** - Utility-first CSS
- **Vite** - Build tool & dev server
- **Lord Icon** - Animated icons

---

## Instalasi

### Prerequisites
- PHP 8.2 atau lebih tinggi
- Composer
- Node.js 18+ dan NPM
- MySQL atau SQLite

### Setup Project

1. **Clone Repository**
```bash
git clone <repository-url>
cd ramadan-growth
```

2. **Install Dependencies**
```bash
composer install
npm install
```

3. **Environment Configuration**
```bash
cp .env.example .env
php artisan key:generate
```

4. **Database Setup**

Edit `.env` sesuai database Anda:

**SQLite:**
```env
DB_CONNECTION=sqlite
```

**MySQL:**
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ramadan_growth
DB_USERNAME=root
DB_PASSWORD=
```

5. **Run Migrations & Seeder**
```bash
php artisan migrate --seed
```

6. **Build Assets & Start Server**
```bash
# Development (2 terminal)
php artisan serve    # Terminal 1: Backend
npm run dev          # Terminal 2: Frontend Vite

# Atau gunakan composer script
composer dev
```

7. **Access Application**

Buka browser: `http://localhost:8000`

**Default Admin Account:**
- Email: `admin@example.com`
- Password: `password`

---

## Panduan Penggunaan

### Mengisi Checklist Ibadah

**Untuk Hari Ini:**
- Masuk ke Dashboard
- Klik pada item ibadah untuk menandai sebagai selesai
- Checklist akan auto-save otomatis

**Untuk Hari Lain (Backfill):**
- Gunakan tombol navigasi untuk pindah ke tanggal lain
- Klik item ibadah untuk mengisi log
- Klik "Kembali ke Hari Ini" untuk kembali

### Memahami Perfect Day
- Perfect Day = Shalat 5 Waktu + Puasa (semua wajib tercentang)
- Sunnah **tidak wajib** untuk perfect day
- Streak bertambah setiap hari berturut-turut mencapai perfect day

### Mengatur Profile
- Klik avatar di navbar untuk akses menu profil
- Edit nama, email, atau avatar
- Update password jika perlu

---

## Struktur Project

```
app/
├── Http/Controllers/
│   ├── DashboardController.php
│   ├── DailyLogController.php
│   ├── ProfileController.php
│   └── Admin/UserController.php
├── Models/
│   ├── User.php
│   └── DailyLog.php
└── Services/
    ├── IbadahService.php
    ├── PerfectDayCalculator.php
    └── StreakService.php

resources/js/
├── Pages/
│   ├── Dashboard.vue
│   ├── Welcome.vue
│   ├── Auth/
│   ├── Profile/
│   └── Admin/
├── Components/
└── Layouts/
```

---

## Customization

### Mengubah Ibadah List
Edit `app/Services/IbadahService.php` untuk menambah/mengurangi item ibadah.

### Mengubah Perfect Day Requirements
Edit `IbadahService::WAJIB` untuk menentukan ibadah mana yang wajib untuk perfect day.

---

## Troubleshooting

### Vite manifest not found
```bash
npm run dev
# atau untuk production:
npm run build
```

### Database error setelah seeding
```bash
php artisan migrate:fresh --seed
```

### Permission denied (Linux/Mac)
```bash
chmod -R 775 storage bootstrap/cache
```

---

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

---

<div align="center">

**Made with love during Ramadan**

</div>
