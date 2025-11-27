# Sistem Informasi Geografis Desa Mayong Lor

![Laravel](https://img.shields.io/badge/Laravel-12-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-3.4-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)
![Alpine.js](https://img.shields.io/badge/Alpine.js-3.4-8BC0D0?style=for-the-badge&logo=alpine.js&logoColor=white)
![Leaflet](https://img.shields.io/badge/Leaflet-1.9-199900?style=for-the-badge&logo=leaflet&logoColor=white)

## Deskripsi

**Sistem Informasi Geografis (SIG) Desa Mayong Lor** adalah aplikasi berbasis web yang dirancang untuk memetakan dan mengelola data geospasial Desa Mayong Lor. Aplikasi ini memudahkan pemerintah desa dan masyarakat untuk mengakses informasi mengenai lokasi-lokasi penting, batas wilayah, infrastruktur, dan penggunaan lahan di desa.

## Fitur Utama

-   **Peta Interaktif**: Jelajahi peta desa dengan fitur zoom, pan, dan layer yang dapat diaktifkan/dinonaktifkan (Places, Boundaries, Infrastructures, Land Uses).
-   **Dashboard Admin**: Halaman khusus admin untuk melihat ringkasan data dan mengakses menu pengelolaan.
-   **Manajemen Data (CRUD)**:
    -   **Places**: Kelola data tempat-tempat penting (sekolah, kantor desa, tempat ibadah, dll).
    -   **Boundaries**: Kelola data batas wilayah desa/dusun.
    -   **Infrastructures**: Kelola data infrastruktur (jalan, jembatan, drainase).
    -   **Land Uses**: Kelola data penggunaan lahan (pertanian, pemukiman).
-   **Import Data**: Fitur untuk mengimport data geospasial secara massal.
-   **Laporan**: Export data ke format CSV atau lihat laporan dalam format HTML siap cetak.
-   **Landing Page Publik**: Halaman depan yang informatif untuk pengunjung umum.

## Teknologi yang Digunakan

-   **Backend**: [Laravel 12](https://laravel.com)
-   **Frontend**: [Blade Templates](https://laravel.com/docs/blade), [Tailwind CSS](https://tailwindcss.com), [Alpine.js](https://alpinejs.dev)
-   **Peta**: [Leaflet.js](https://leafletjs.com)
-   **Database**: MySQL

## Instalasi

Ikuti langkah-langkah berikut untuk menjalankan proyek ini di komputer lokal Anda:

1.  **Clone Repository**

    ```bash
    git clone https://github.com/username/mayonglor-gis.git
    cd mayonglor-gis
    ```

2.  **Install Dependencies**

    Install PHP dependencies menggunakan Composer dan JavaScript dependencies menggunakan NPM.

    ```bash
    composer install
    npm install
    ```

3.  **Konfigurasi Environment**

    Salin file `.env.example` menjadi `.env` dan sesuaikan konfigurasi database Anda.

    ```bash
    cp .env.example .env
    ```

    Buka file `.env` dan atur koneksi database:

    ```env
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=mayonglor_gis
    DB_USERNAME=root
    DB_PASSWORD=
    ```

4.  **Generate Application Key**

    ```bash
    php artisan key:generate
    ```

5.  **Migrasi Database**

    Jalankan migrasi untuk membuat tabel-tabel database. Anda juga bisa menjalankan seeder jika tersedia.

    ```bash
    php artisan migrate --seed
    ```

6.  **Build Assets**

    Compile file CSS dan JavaScript.

    ```bash
    npm run build
    ```

7.  **Jalankan Server**

    Jalankan server pengembangan lokal.

    ```bash
    php artisan serve
    ```

    Akses aplikasi di browser melalui `http://localhost:8000`.

## Lisensi

Aplikasi ini adalah software open-source di bawah lisensi [MIT license](https://opensource.org/licenses/MIT).
