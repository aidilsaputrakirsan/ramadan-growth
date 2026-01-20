# Ramadan Growth

<div align="center">

![Ramadan Growth](https://img.shields.io/badge/Ramadan-Growth-34d399?style=for-the-badge)
![Laravel](https://img.shields.io/badge/Laravel-12-FF2D20?style=for-the-badge&logo=laravel)
![Vue.js](https://img.shields.io/badge/Vue.js-3-4FC08D?style=for-the-badge&logo=vue.js)
![TypeScript](https://img.shields.io/badge/TypeScript-5-3178C6?style=for-the-badge&logo=typescript)

**Aplikasi Tracker Ibadah Ramadan Personal**

Bangun kebiasaan ibadah yang konsisten selama bulan Ramadan dengan tracking visual yang menarik dan gamifikasi masjid virtual! 🕌

[Instalasi](#-instalasi) • [Panduan](#-panduan-penggunaan) • [Fitur](#-fitur-utama) • [Tech Stack](#-tech-stack)

</div>

---

## 📖 Tentang Aplikasi

**Ramadan Growth** adalah aplikasi web untuk membantu Anda membangun kebiasaan ibadah yang baik selama bulan Ramadan. Aplikasi ini **fokus pada personal growth** tanpa elemen kompetisi - murni untuk tracking progress pribadi Anda sendiri.

### 🎯 Konsep Utama

- **Perfect Day**: Hari sempurna tercapai ketika Anda menyelesaikan semua **ibadah wajib** (Shalat 5 waktu + Puasa)
- **Masjid Evolution**: Visualisasi progress melalui 4 tahap pembangunan masjid virtual berdasarkan jumlah perfect days
- **Streak Tracker**: Hitung berapa hari berturut-turut Anda mencapai perfect day
- **Backfill Support**: Isi log ibadah untuk hari-hari yang terlewat

---

## ✨ Fitur Utama

### 1. 📅 Dashboard dengan Date Picker
- **Navigasi Tanggal**: Lihat dan isi log ibadah untuk tanggal mana pun (tidak hanya hari ini)
- **Tombol Navigasi**: Mundur/maju per hari dengan tombol previous/next
- **Quick Jump**: Tombol "Kembali ke Hari Ini" untuk navigasi cepat
- **Validasi**: Tidak bisa mengisi tanggal di masa depan

### 2. ✅ Checklist Ibadah Harian

**Ibadah Wajib** (Required untuk Perfect Day):
- 🕌 Shalat 5 Waktu
- 🥤 Puasa Ramadan

**Ibadah Sunnah** (Opsional, untuk tambahan amalan):
- 🌙 Shalat Tarawih
- 📖 Tilawah Al-Qur'an
- 🤲 Sedekah
- 🌌 Shalat Tahajud
- ☀️ Shalat Dhuha
- 📿 Dzikir Pagi & Petang

### 3. 📊 Statistik Personal
- **Perfect Days Counter**: Total hari sempurna yang Anda capai
- **Streak Counter**: Berapa hari berturut-turut perfect day
- **Progress Bar**: Visualisasi progress harian (wajib + sunnah)
- **Auto-save**: Otomatis menyimpan setiap perubahan

### 4. 🕌 Masjid Evolution
Visualisasi progress melalui 4 tahap masjid:
- **Stage 1** (0-6 perfect days): Tanah kosong / fondasi
- **Stage 2** (7-14 perfect days): Struktur bangunan
- **Stage 3** (15-21 perfect days): Kubah dan menara
- **Stage 4** (22+ perfect days): Masjid lengkap

### 5. 👤 Profile Management
- Edit nama dan email
- Pilih avatar dari 10+ pilihan
- Update password
- Delete account

### 6. 👨‍💼 Admin Panel (untuk Admin)
- User management
- Reset password user
- Hapus user

### 7. 🔐 Authentication
- Register akun baru
- Login
- Forgot password
- Email verification (optional)

---

## 🛠 Tech Stack

### Backend
- **Laravel 12** - PHP Framework
- **PHP 8.2+** - Language
- **SQLite** - Database (default, bisa diganti MySQL/PostgreSQL)
- **Inertia.js** - Server-side routing with SPA experience

### Frontend
- **Vue 3** - Progressive JavaScript Framework
- **TypeScript** - Type-safe JavaScript
- **Tailwind CSS v4** - Utility-first CSS
- **Vite** - Build tool & dev server

### UI/UX Libraries
- **ApexCharts** - Grafik & visualisasi data
- **Lottie** - Animasi
- **Lord Icon** - Icon animasi

---

## 📦 Instalasi

### Prerequisites
- PHP 8.2 atau lebih tinggi
- Composer
- Node.js 18+ dan NPM
- SQLite3 (atau MySQL/PostgreSQL)

### Setup Project

1. **Clone Repository**
```bash
git clone <repository-url>
cd ramadan-growth
```

2. **Install Dependencies**
```bash
# Install PHP dependencies
composer install

# Install JavaScript dependencies
npm install
```

3. **Environment Configuration**
```bash
# Copy file .env.example
copy .env.example .env

# Generate application key
php artisan key:generate
```

4. **Database Setup**

Aplikasi menggunakan SQLite secara default. File database akan otomatis dibuat saat migrasi.

Jika ingin menggunakan MySQL/PostgreSQL, edit `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ramadan_growth
DB_USERNAME=root
DB_PASSWORD=
```

5. **Run Migrations**
```bash
php artisan migrate
```

6. **Build Assets**
```bash
# Development
npm run dev

# Production
npm run build
```

7. **Start Application**
```bash
# Development mode (dengan hot reload)
composer dev

# Atau manual:
php artisan serve  # Backend di http://localhost:8000
npm run dev        # Frontend Vite di http://localhost:5173
```

8. **Access Application**
Buka browser dan akses `http://localhost:8000`

---

## 🚀 Panduan Penggunaan

### 1. Register & Login
- Klik **Register** untuk membuat akun baru
- Isi nama, email, dan password
- Login dengan credentials yang sudah dibuat

### 2. Mengisi Checklist Ibadah

**Untuk Hari Ini:**
- Masuk ke Dashboard
- Klik pada item ibadah untuk menandai sebagai selesai
- Checklist akan **auto-save** otomatis

**Untuk Hari Lain (Backfill):**
- Gunakan tombol **◀️ / ▶️** untuk navigasi ke tanggal lain
- Klik item ibadah untuk mengisi log
- Klik **"Kembali ke Hari Ini"** untuk kembali ke hari ini

### 3. Memahami Perfect Day
- Perfect Day = ✅ Shalat 5 Waktu + ✅ Puasa
- Sunnah **tidak wajib** untuk perfect day
- Sunnah hanya untuk menambah amalan

### 4. Melihat Progress
- **Perfect Days**: Total hari dengan semua wajib tercentang
- **Streak**: Berapa hari berturut-turut perfect day (tidak putus)
- **Masjid Stage**: Lihat evolusi masjid Anda di sidebar/komponen

### 5. Mengatur Profile
- Klik menu **Profil**
- Edit nama atau email
- Pilih avatar favorit
- Update password jika perlu

### 6. Admin (Khusus User Admin)
- Klik menu **Admin**
- Lihat daftar semua user
- Reset password user lain
- Hapus user jika diperlukan

---

## 🗂 Struktur Database

### Users Table
```
- id
- name
- email
- password
- avatar (nullable)
- is_admin (default: false)
- email_verified_at
- timestamps
```

### Daily Logs Table
```
- id
- user_id
- date
- tasks_completed (JSON: {shalat_5_waktu: bool, puasa: bool, ...})
- is_perfect_day (boolean)
- timestamps
```

---

## 🔧 Development

### Composer Scripts

```bash
# Setup lengkap (install + migrate)
composer setup

# Run development server (backend + frontend + queue + logs)
composer dev

# Run tests
composer test
```

### NPM Scripts

```bash
# Development dengan hot reload
npm run dev

# Build untuk production
npm run build
```

---

## 🎨 Customization

### Mengubah Ibadah List
Edit file `app/Services/IbadahService.php` untuk menambah/mengurangi item ibadah.

### Mengubah Perfect Day Requirements
Edit `IbadahService::REQUIRED_FOR_PERFECT` untuk menentukan ibadah mana yang wajib untuk perfect day.

### Mengubah Masjid Stages
Edit `app/Services/PerfectDayCalculator.php` method `getMasjidStage()` untuk mengubah threshold setiap tahap.

---

## 🐛 Troubleshooting

### Error: "No such file or directory (database.sqlite)"
```bash
# Buat file database manual
touch database/database.sqlite

# Atau di Windows
type nul > database\database.sqlite

# Kemudian run migrations
php artisan migrate
```

### Error: "Permission denied" (Linux/Mac)
```bash
chmod -R 775 storage bootstrap/cache
```

### Vite Error: "Failed to resolve import"
```bash
# Clear cache dan reinstall
rm -rf node_modules
npm install
npm run dev
```

---

## 📝 License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

---

## 🤝 Contributing

Kontribusi, issues, dan feature requests sangat diterima!

---

<div align="center">

**Made with ❤️ during Ramadan**

بارك الله فيك

</div>
