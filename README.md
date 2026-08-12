# IMBANGIN - Fullstack Health & Wellness Tracker

IMBANGIN adalah aplikasi pemantau kesehatan dan kebugaran berbasis web fullstack. Aplikasi ini dirancang untuk membantu pengguna melacak aktivitas harian, menjaga gaya hidup sehat, dan memberikan pengalaman interaktif melalui sistem kalkulasi gamifikasi otomatis.

## Fitur Utama
- Autentikasi Aman (Token-Based): Sistem registrasi dan login menggunakan skema autentikasi berbasis token (Bearer Token) untuk mengamankan data pengguna.
- Health & Wellness Tracking: Fitur untuk mencatat dan memantau progres harian pengguna seperti aktivitas fisik dan target harian.
- Kalkulasi Gamifikasi Otomatis: Engine logic pada backend yang secara otomatis menghitung skor, poin, atau badge berdasarkan aktivitas yang berhasil diselesaikan pengguna.
- RESTful API Architecture: Arsitektur backend yang rapi dan terstruktur, siap dikonsumsi oleh berbagai platform frontend.

## Tech Stack

Backend:
- Framework: Laravel (PHP)
- Database: MySQL / PostgreSQL
- Authentication: Laravel Sanctum / Token-Based Auth
- Deployment: Railway

Frontend:
- Library: React.js (Vite)
- Styling: Tailwind CSS
- Deployment: Vercel

## Arsitektur dan System Flow

[ React.js Frontend ] <--- HTTPS / JSON ---> [ Laravel RESTful API ] <---> [ Database ]
   (Hosted on Vercel)                             (Hosted on Railway)

- Frontend (React) menangani antarmuka pengguna, navigasi visual, dan pemanggilan API via Axios.
- Backend (Laravel) memproses business logic, validasi data, autentikasi pengguna, dan kalkulasi gamifikasi.

## Panduan Jalankan Proyek di Lokal

Prasyarat:
- PHP >= 8.x
- Composer
- Node.js & npm
- Database Server (MySQL/PostgreSQL)

1. Setup Backend (Laravel):
git clone https://github.com/alamm0/imbangin-backend.git
cd imbangin-backend
composer install
cp .env.example .env
php artisan key:generate

Isi variabel berikut di file .env:
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=imbangin
DB_USERNAME=root
DB_PASSWORD=

Lalu jalankan:
php artisan migrate
php artisan serve

2. Setup Frontend (React + Vite):
git clone https://github.com/alamm0/imbangin-frontend.git
cd imbangin-frontend
npm install
npm run dev

## Live Demo
- Imbangin : https://imbangin.vercel.app

## Pengembang
- Muhammad Satria Alam - Back-end & DevOps Enthusiast
- GitHub: https://github.com/alamm0
- LinkedIn: https://www.linkedin.com/in/muhammad-satria-alam-356039402