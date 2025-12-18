# 🗺️ Sistem Informasi Geografis Desa Mayong Lor

![Banner Project](/public/images/landing-page.png)

> **Platform pemetaan digital terintegrasi untuk transparansi data, pembangunan infrastruktur, dan pelayanan publik Desa Mayong Lor, Kabupaten Jepara.**

---

## 📖 Tentang Project

**Mayong Lor GIS** adalah aplikasi web berbasis *Geographic Information System* (GIS) modern yang dikembangkan untuk mendigitalisasi aset dan potensi wilayah Desa Mayong Lor. Aplikasi ini berfungsi sebagai pusat data spasial yang dapat diakses oleh masyarakat umum maupun perangkat desa.

Tujuan utama aplikasi ini adalah:
1.  **Transparansi Publik**: Memberikan akses terbuka terhadap data infrastruktur, penggunaan lahan, dan batas wilayah.
2.  **Perencanaan Pembangunan**: Membantu pemerintah desa dalam mengambil keputusan berbasis data spasial.
3.  **Promosi Potensi Desa**: Menampilkan lokasi-lokasi strategis seperti UMKM, pariwisata, dan fasilitas umum.

## ✨ Fitur Utama

### 🌍 Halaman Publik (Landing Page)
-   **Hero Map 3D**: Peta interaktif 3D menggunakan MapLibre GL JS untuk visualisasi wilayah yang memukau.
-   **Statistik Desa**: Ringkasan data kependudukan, luas wilayah, dan potensi desa secara *real-time*.
-   **Berita & Pengumuman**: Informasi terbaru seputar kegiatan desa.
-   **Peta Jelajah (Explore Map)**:
    -   🔍 **Pencarian Lokasi**: Cari dukuh, jalan, atau bangunan publik dengan cepat.
    -   🗺️ **Layer Control**: Toggle layer Batas Wilayah (Sawah, Pemukiman), Infrastruktur (Jalan, Sungai), dan Penggunaan Lahan.
    -   📍 **Kategori Lokasi**: Filter lokasi berdasarkan kategori (Pendidikan, Kesehatan, Pemerintahan, dll).
    -   📱 **Responsif Mobile**: Tampilan peta yang optimal di perangkat *mobile* (smartphone/tablet).

### 🔐 Admin Dashboard
-   **Manajemen Lokasi (Places)**: CRUD data lokasi penting beserta koordinat dan informasinya.
-   **Manajemen Spasial**:
    -   Input data **Batas Wilayah** (Polygon).
    -   Input data **Jalan & Sungai** (Polyline).
    -   Input data **Penggunaan Lahan** (Polygon/Area).
-   **Data Kependudukan**: Update data statistik populasi desa.
-   **Import & Export**: Fitur import data GeoJSON dan export laporan ke format CSV/HTML.

## 🛠️ Tech Stack

Project ini dibangun dengan stack teknologi *monolith* modern yang handal:

| Kategori | Teknologi | Deskripsi |
| :--- | :--- | :--- |
| **Framework** | ![Laravel](https://img.shields.io/badge/Laravel-FF2D20?style=flat-square&logo=laravel&logoColor=white) | Framework PHP utama (v11/12). |
| **Database** | ![MySQL](https://img.shields.io/badge/MySQL-4479A1?style=flat-square&logo=mysql&logoColor=white) | Penyimpanan data relasional & spasial. |
| **Frontend** | ![TailwindCSS](https://img.shields.io/badge/Tailwind_CSS-38B2AC?style=flat-square&logo=tailwind-css&logoColor=white) | Framework CSS *utility-first* untuk styling. |
| **Interactivity** | ![Alpine.js](https://img.shields.io/badge/Alpine.js-8BC0D0?style=flat-square&logo=alpine.js&logoColor=white) | Framework JS ringan untuk interaksi frontend. |
| **Interactive Map** | ![Leaflet](https://img.shields.io/badge/Leaflet-199900?style=flat-square&logo=leaflet&logoColor=white) | Library peta utama (2D). |
| **3D Map** | ![MapLibre](https://img.shields.io/badge/MapLibre-1A1E29?style=flat-square&logo=maplibre&logoColor=white) | Library peta 3D untuk Hero Section. |

## ⚙️ Prasyarat (Prerequisites)

Pastikan lingkungan kerja Anda sudah terinstal:
-   **PHP** >= 8.2
-   **Composer** (PHP Dependency Manager)
-   **Node.js** & **NPM** (untuk build assets)
-   **MySQL** / MariaDB (support spatial extensions)

## 🚀 Cara Instalasi & Menjalankan Project

Ikuti langkah-langkah berikut untuk setup project di komputer lokal (Localhost):

### 1. Clone Repository
```bash
git clone https://github.com/danyakmallun9999/landing-page-mayonglor-gis.git
cd landing-page-mayonglor-gis
```

### 2. Install Dependencies
Install paket PHP dan JavaScript yang dibutuhkan:
```bash
composer install
npm install
```

### 3. Konfigurasi Environment
Salin file contoh `.env` dan sesuaikan konfigurasi database:
```bash
cp .env.example .env
```
Buka file `.env` dan atur detail database Anda:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_port=3306
DB_DATABASE=mayonglor_gis  # Pastikan DB ini sudah dibuat
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Setup Database & Key
Generate Application Key, jalankan migrasi database, dan (opsional) seeder data dummy:
```bash
php artisan key:generate
php artisan migrate --seed  # --seed akan mengisi data awal
```

### 5. Build Assets & Run Server
Jalankan perintah berikut untuk meng-compile aset (CSS/JS) dan menjalankan server development:
```bash
# Jalankan server development (Laravel + Vite)
composer run dev
```

Aplikasi dapat diakses melalui browser di: **[http://localhost:8000](http://localhost:8000)**

## 👤 Akun Admin Default

Jika Anda menjalankan `php artisan migrate --seed`, akun admin default berikut akan dibuat:

-   **Email**: `admin@mayonglor.id` (atau cek `DatabaseSeeder.php`)
-   **Password**: `password`

## 📁 Struktur Direktori Penting

-   `app/Http/Controllers`: Logika backend (Admin, Map, API).
-   `resources/views`: Tampilan frontend (Blade Templates).
    -   `welcome.blade.php`: Halaman utama / Landing Page.
    -   `explore-map.blade.php`: Halaman peta interaktif full-screen.
-   `routes/web.php`: Definisi rute aplikasi.
-   `public/`: Aset publik (gambar, file statis).

## 🤝 Kontribusi

Kontribusi selalu terbuka! Jika Anda ingin meningkatkan fitur atau memperbaiki bug:
1.  Fork repository ini.
2.  Buat branch fitur baru (`git checkout -b fitur-keren`).
3.  Commit perubahan Anda (`git commit -m 'Menambahkan fitur keren'`).
4.  Push ke branch (`git push origin fitur-keren`).
5.  Buat Pull Request.

---

**© 2025 Pemerintah Desa Mayong Lor**  
*Dikembangkan untuk kemajuan desa dan kesejahteraan masyarakat.*
