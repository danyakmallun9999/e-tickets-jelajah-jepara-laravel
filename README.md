# Jelajah Jepara - Portal Resmi Pariwisata Kabupaten Jepara

> **Platform digital terintegrasi untuk promosi pariwisata, ekonomi kreatif, dan e-ticketing Kabupaten Jepara.**

---

## Tentang Project

**Jelajah Jepara** adalah portal web modern yang dikembangkan oleh Mahasiswa Magang Unisnu Jepara Jurusan Teknik Informatika. Aplikasi ini mendigitalisasi sektor pariwisata mulai dari promosi destinasi, pengelolaan konten budaya, hingga transaksi tiket masuk secara elektronik.

Tujuan utama aplikasi ini:

1. **Promosi Wisata** — Menampilkan destinasi wisata unggulan Jepara dengan visualisasi peta interaktif 3D dan galeri media.
2. **E-Ticketing System** — Memudahkan wisatawan membeli tiket masuk secara online (cashless) dengan berbagai metode pembayaran digital.
3. **Sistem Informasi GIS** — Menyediakan peta interaktif berbasis GIS untuk eksplorasi wisata, infrastruktur, batas wilayah, dan tata guna lahan.
4. **Informasi Terpusat** — Menyajikan berita, agenda budaya, legenda daerah, dan data statistik pariwisata secara real-time.
5. **Pengelolaan Konten** — Dashboard admin dengan role-based access control untuk manajemen konten multi-bahasa.

---

## Fitur Utama

### Halaman Publik (Landing Page)

- **Hero Map 3D** — Visualisasi lanskap Kabupaten Jepara menggunakan **MapLibre GL JS** dengan terrain 3D.
- **Jelajah Peta (Interactive GIS)** — Pencarian lokasi wisata, hotel, kuliner, infrastruktur, dan batas wilayah berbasis peta interaktif menggunakan **Leaflet** dengan plugin MarkerCluster, Draw, dan Routing Machine.
- **Multilingual Support** — Tersedia dalam Bahasa Indonesia dan Bahasa Inggris dengan file terjemahan JSON (`lang/id.json`, `lang/en.json`).
- **Terjemahan Konten Otomatis** — Dukungan terjemahan konten menggunakan **Google Translate API** (via `stichoza/google-translate-php`) dengan fallback ke **MyMemory Translation API**.
- **Katalog Destinasi** — Halaman listing dan detail destinasi wisata dengan galeri multi-gambar, informasi tiket, jam operasional, dan lokasi peta.
- **Budaya & Kuliner** — Halaman kategori dan detail untuk konten budaya dan kuliner daerah lengkap dengan lokasi GeoJSON.
- **Legenda Daerah** — Koleksi cerita dan legenda khas Jepara.
- **Blog / Berita** — Sistem artikel dengan dukungan dual-format editor (Editor.js dan TinyMCE), penghitung views, dan SEO-optimized.
- **Agenda / Event** — Listing event dan agenda pariwisata dengan detail lengkap.
- **Travel Agencies** — Direktori agen perjalanan di Jepara.
- **Destinasi Unggulan (Flagship)** — Halaman khusus destinasi unggulan seperti Karimunjawa.
- **Pengumuman (Announcement)** — Sistem pengumuman yang tampil di halaman publik.
- **Animasi & Micro-interaction** — Transisi halaman dan animasi scroll menggunakan **GSAP** dan **Alpine.js Intersect**.
- **Dark Mode** — Dukungan tema gelap dengan konfigurasi Tailwind CSS class-based toggle.
- **SEO Optimized** — Dilengkapi Open Graph tags, meta description, heading hierarchy, dan semantic HTML.

### E-Ticketing System

- **Booking Online** — Wisatawan dapat memesan tiket masuk untuk berbagai destinasi wisata.
- **Pembayaran Digital** — Terintegrasi dengan **Midtrans Core API** mendukung metode:
    - QRIS (scan QR universal)
    - GoPay
    - ShopeePay
    - Virtual Account (BCA, BNI, BRI)
    - Mandiri Bill Payment (e-Channel)
- **Tiket QR Code** — Tiket elektronik dengan QR Code unik (via `bacon/bacon-qr-code` dan library `qrcode` JS) untuk scan di pintu masuk.
- **QR Code Scanner** — Sistem scan QR di pintu masuk menggunakan **html5-qrcode** untuk validasi tiket secara real-time.
- **Riwayat Tiket** — Halaman "Tiket Saya" untuk wisatawan melihat riwayat pembelian dan status tiket.
- **Webhook Midtrans** — Penanganan notifikasi pembayaran otomatis dengan IP whitelisting dan verifikasi signature SHA-512.
- **Livewire Real-time** — Manajemen order tiket di admin menggunakan **Livewire v4** untuk pencarian, filter, dan paginasi real-time tanpa reload halaman.
- **Laporan Keuangan** — Dashboard pendapatan real-time dengan dukungan export CSV dan laporan HTML.

### Role-Based Access Control (RBAC)

Sistem memiliki manajemen hak akses yang detail menggunakan **Spatie Laravel Permission** untuk berbagai peran pengguna:

1. **Super Admin** — Akses penuh ke seluruh sistem, manajemen user, konfigurasi hero/footer, dan pengaturan global.
2. **Admin Wisata** — Mengelola data destinasi wisata, tiket, laporan keuangan, scan tiket, dan travel agencies.
3. **Admin Berita** — Fokus pada publikasi artikel, berita, event, budaya, legenda, dan pengumuman.
4. **Pengelola Wisata** — Akun khusus untuk petugas di lapangan (scan tiket, validasi di pintu masuk).

### Dashboard Admin

- **Statistik Real-time** — Ringkasan jumlah destinasi, post, event, tiket terjual, dan pendapatan.
- **Grafik & Chart** — Visualisasi data menggunakan **ApexCharts** dan **Chart.js** (tren kunjungan, pendapatan mingguan, views post).
- **Editor Konten Dual-Format**:
    - **Editor.js** — Editor block-based modern dengan 15+ plugin (header, paragraph, list, image, table, quote, embed, checklist, code, delimiter, underline, marker, warning, text alignment, text color, drag-drop, undo/redo, strikethrough).
    - **TinyMCE** — Editor WYSIWYG untuk konten HTML legacy.
- **Media Gallery** — Sistem upload dan manajemen multi-media untuk destinasi, budaya, dan hero settings.
- **Hero Settings** — Konfigurasi hero section landing page (video/gambar desktop & mobile) langsung dari admin.
- **Footer Settings** — Konfigurasi konten footer dari admin.
- **Terjemahan Konten** — Fitur terjemahan otomatis konten dari admin panel.
- **Activity Log** — Pencatatan aktivitas pengguna admin.

---

## Tech Stack

### Backend

| Teknologi            | Versi          | Fungsi                                                   |
| :------------------- | :------------- | :------------------------------------------------------- |
| PHP                  | >= 8.2         | Bahasa pemrograman server-side                           |
| Laravel              | v12            | Framework PHP utama                                      |
| Livewire             | v4             | Komponen reaktif real-time (manajemen order tiket)       |
| Spatie Permission    | v6             | Manajemen Role & Permission (RBAC)                       |
| Laravel Socialite    | v5             | OAuth authentication (Google Login)                      |
| Laravel Breeze       | v2             | Scaffolding autentikasi admin                            |
| Midtrans PHP         | v2.5           | Payment Gateway SDK                                      |
| Intervention Image   | v3             | Manipulasi dan optimasi gambar                           |
| Bacon QR Code        | v3             | Generasi QR Code tiket                                   |
| Mews Purifier        | v3             | Sanitasi HTML (keamanan konten)                          |
| Google Translate PHP | v5 (stichoza)  | API terjemahan otomatis (primary)                        |
| MyMemory API         | —              | API terjemahan (fallback)                                |
| NoCaptcha            | v3 (anhskohbo) | Google reCAPTCHA integration                             |
| Laravolt Indonesia   | v0.39          | Data wilayah Indonesia (provinsi, kota, kecamatan, desa) |
| Flysystem AWS S3     | v3             | Penyimpanan file cloud (opsional)                        |

### Frontend

| Teknologi           | Versi | Fungsi                                           |
| :------------------ | :---- | :----------------------------------------------- |
| Vite                | v7    | Build tool & dev server                          |
| Tailwind CSS        | v3    | Framework CSS utility-first                      |
| Alpine.js           | v3    | Framework JavaScript ringan untuk interaktivitas |
| Alpine.js Intersect | v3    | Plugin untuk animasi on-scroll                   |
| Alpine.js Morph     | v3    | Plugin untuk transisi DOM smooth                 |

### Peta & GIS

| Teknologi               | Versi | Fungsi                                             |
| :---------------------- | :---- | :------------------------------------------------- |
| MapLibre GL JS          | v5    | Visualisasi peta 3D interaktif (hero section)      |
| Leaflet                 | v1.9  | Peta interaktif 2D (explore map, detail destinasi) |
| Leaflet Draw            | v1.0  | Menggambar polygon/marker di peta (admin boundary) |
| Leaflet Routing Machine | v3.2  | Navigasi rute ke destinasi                         |
| Leaflet MarkerCluster   | v1.5  | Pengelompokan marker untuk performa peta           |

### Content Editor

| Teknologi               | Versi | Fungsi                                  |
| :---------------------- | :---- | :-------------------------------------- |
| Editor.js               | v2.31 | Editor block-based modern (post, event) |
| — Plugin: Header        | v2.8  | Blok heading H1-H6                      |
| — Plugin: Image         | v2.10 | Blok gambar dengan upload               |
| — Plugin: List          | v2.0  | Ordered & unordered list                |
| — Plugin: Table         | v2.4  | Blok tabel                              |
| — Plugin: Quote         | v2.7  | Blok kutipan                            |
| — Plugin: Code          | v2.9  | Blok kode                               |
| — Plugin: Embed         | v2.8  | Embed YouTube, dll                      |
| — Plugin: Checklist     | v1.6  | Checklist interaktif                    |
| — Plugin: Delimiter     | v1.4  | Pemisah konten                          |
| — Plugin: Marker        | v1.4  | Highlight teks                          |
| — Plugin: Inline Code   | v1.5  | Kode inline                             |
| — Plugin: Underline     | v1.2  | Garis bawah teks                        |
| — Plugin: Warning       | v1.4  | Blok peringatan                         |
| — Plugin: Raw HTML      | v2.5  | Blok HTML mentah                        |
| — Plugin: Text Variant  | v1.0  | Variasi style teks                      |
| — Addon: Drag & Drop    | v1.1  | Drag-drop blok                          |
| — Addon: Undo/Redo      | v2.0  | Fitur undo/redo                         |
| — Addon: Text Alignment | v1.0  | Perataan teks                           |
| — Addon: Text Color     | v2.0  | Warna teks kustom                       |
| — Addon: Strikethrough  | v1.0  | Coretan teks                            |
| TinyMCE                 | v8    | Editor WYSIWYG (legacy content support) |

### Charting & Visualisasi

| Teknologi  | Versi | Fungsi                                        |
| :--------- | :---- | :-------------------------------------------- |
| ApexCharts | v5    | Grafik interaktif dashboard (tren, statistik) |
| Chart.js   | v4    | Grafik statistik pariwisata                   |
| GSAP       | v3    | Animasi scroll & transisi landing page        |

### Utilitas & Lainnya

| Teknologi        | Versi | Fungsi                                      |
| :--------------- | :---- | :------------------------------------------ |
| html5-qrcode     | v2.3  | QR Code scanner (scan tiket di pintu masuk) |
| qrcode (JS)      | v1.5  | Generasi QR Code di frontend                |
| html2canvas      | v1.4  | Screenshot/capture elemen HTML              |
| Puppeteer        | v24   | Browser headless (PDF report generation)    |
| Axios            | v1    | HTTP client untuk AJAX requests             |
| Font Awesome     | v7    | Ikon UI                                     |
| Material Symbols | v0.40 | Ikon Material Design                        |

### Database

| Teknologi          | Fungsi                                |
| :----------------- | :------------------------------------ |
| MySQL / MariaDB    | Penyimpanan data relasional & spasial |
| Queue (database)   | Job queue untuk proses background     |
| Cache (database)   | Caching data                          |
| Session (database) | Penyimpanan session                   |

### Font

Aplikasi menggunakan beberapa font kustom via `@fontsource`:

- **Inter** — Font sans-serif utama
- **Figtree** — Font sans-serif alternatif
- **Plus Jakarta Sans** — Font display heading
- **Poppins** — Font display alternatif
- **Playfair Display** — Font serif untuk aksen
- **Noto Sans** — Font multi-script
- **Noto Serif** — Font serif multi-script
- **Pinyon Script** — Font kursif dekoratif
- **Caveat** — Font handwriting
- **Press Start 2P** — Font pixel art (aksen khusus)

---

## Arsitektur Aplikasi

### Pola Desain (Design Patterns)

- **MVC** — Model-View-Controller standar Laravel.
- **Service Layer** — Logika bisnis dipisahkan ke kelas Service (`MidtransService`, `DashboardService`, `GeoJsonService`, `ContentRenderer`, dll).
- **Repository Pattern** — Abstraksi data access via `Contracts` (interface) dan `Eloquent` (implementasi).
- **Policy Authorization** — Otorisasi granular menggunakan Laravel Policy (`PlacePolicy`, `PostPolicy`, `TicketPolicy`, dll).
- **Form Request Validation** — Validasi input terpisah dari controller.
- **Feature Flags** — Fitur dapat diaktifkan/nonaktifkan via `.env` tanpa mengubah kode.

### Custom Middleware

| Middleware                | Fungsi                                           |
| :------------------------ | :----------------------------------------------- |
| `CheckPermission`         | Verifikasi permission berbasis Spatie            |
| `EnsureUserAuthenticated` | Autentikasi user publik (Google OAuth)           |
| `MidtransIpWhitelist`     | Whitelist IP untuk webhook Midtrans              |
| `SecurityHeaders`         | Header keamanan HTTP (CSP, X-Frame-Options, dll) |
| `SetLocale`               | Pengaturan bahasa berdasarkan preferensi user    |

### Artisan Commands

| Command              | Fungsi                          |
| :------------------- | :------------------------------ |
| `import:geojson`     | Import file GeoJSON ke database |
| `media:sync-gallery` | Sinkronisasi galeri media       |
| `features:verify`    | Verifikasi status feature flags |

### Service Layer

| Service                  | Fungsi                                                 |
| :----------------------- | :----------------------------------------------------- |
| `MidtransService`        | Integrasi pembayaran (charge, status, cancel, webhook) |
| `DashboardService`       | Statistik dan tren dashboard admin                     |
| `GeoJsonService`         | Konversi data spatial ke format GeoJSON                |
| `ContentRenderer`        | Rendering konten dual-format (HTML & Editor.js JSON)   |
| `ContentSanitizer`       | Sanitasi konten HTML                                   |
| `FileService`            | Upload dan manajemen file                              |
| `PlaceService`           | Logika bisnis destinasi                                |
| `FinancialReportService` | Laporan keuangan e-ticket                              |
| `TicketAnalyticsService` | Analitik tiket                                         |
| `PostStatService`        | Statistik pembacaan artikel                            |
| `ReportExportService`    | Export laporan (CSV, HTML)                             |

---

## Feature Flags

Aplikasi dilengkapi dengan feature toggles untuk mengaktifkan/menonaktifkan fitur tanpa mengubah kode. Pengaturan dilakukan melalui file `.env`.

### E-Ticketing System

Mengaktifkan/menonaktifkan seluruh sistem tiket (penjualan, laporan, menu tiket, scan):

```env
FEATURE_E_TICKET=true   # Aktifkan fitur
FEATURE_E_TICKET=false  # Nonaktifkan fitur (default)
```

### Google Login

Mengaktifkan/menonaktifkan fitur login menggunakan akun Google untuk wisatawan:

```env
FEATURE_GOOGLE_LOGIN=true   # Aktifkan login Google
FEATURE_GOOGLE_LOGIN=false  # Nonaktifkan login Google
```

> **Catatan:** Setelah mengubah nilai di `.env`, jalankan perintah `php artisan config:clear` agar perubahan diterapkan. Gunakan `php artisan features:verify` untuk memvalidasi status fitur.

---

## Prasyarat (Prerequisites)

- **PHP** >= 8.2 (dengan ekstensi: `gd`, `mbstring`, `pdo_mysql`, `xml`, `curl`)
- **Composer** (dependency manager PHP)
- **Node.js** >= 18 & **NPM**
- **MySQL** / MariaDB (dengan dukungan spatial extensions)

---

## Cara Instalasi

### 1. Clone Repository

```bash
git clone https://github.com/danyakmallun9999/dinas-pariwisata.git
cd dinas-pariwisata
```

### 2. Install Dependencies

```bash
composer install
npm install
```

### 3. Setup Environment

```bash
cp .env.example .env
php artisan key:generate
```

Atur konfigurasi di `.env`:

```env
# Database
DB_DATABASE=dinas_pariwisata
DB_USERNAME=root
DB_PASSWORD=

# Midtrans Payment Gateway (opsional, untuk fitur e-ticket)
MIDTRANS_SERVER_KEY=your_server_key
MIDTRANS_CLIENT_KEY=your_client_key
MIDTRANS_MERCHANT_ID=your_merchant_id

# Google OAuth (opsional, untuk fitur login Google)
GOOGLE_CLIENT_ID=your_client_id
GOOGLE_CLIENT_SECRET=your_client_secret
GOOGLE_REDIRECT_URL=http://127.0.0.1:8000/auth/google/callback

# reCAPTCHA (opsional)
NOCAPTCHA_SITEKEY=your_sitekey
NOCAPTCHA_SECRET=your_secret

# Feature Flags
FEATURE_E_TICKET=false
FEATURE_GOOGLE_LOGIN=false

# Admin Password
INITIAL_ADMIN_PASSWORD=your_secure_password
SAMPLE_ADMIN_PASSWORD=password
```

### 4. Setup Database & Seeders

```bash
php artisan migrate:fresh --seed
```

Perintah ini akan membuat database dan mengisi data awal (destinasi wisata, user admin, kategori, event, budaya, legenda, statistik pariwisata, dan tiket dummy jika fitur e-ticket aktif).

### 5. Jalankan Server

```bash
composer run dev
```

Perintah ini menjalankan secara bersamaan:

- **Laravel Server** — `php artisan serve` (port 8000)
- **Queue Worker** — `php artisan queue:listen` (untuk proses background)
- **Vite Dev Server** — `npm run dev` (hot-reload untuk frontend)

Akses aplikasi di: **http://localhost:8000**

---

## Akun Admin Default

**Catatan Keamanan:** Password admin menggunakan environment variables.

### Setup Environment Variables

Tambahkan ke file `.env`:

```env
# Super Admin Password (wajib untuk production)
INITIAL_ADMIN_PASSWORD=your_secure_password_here

# Sample Admin Password (hanya untuk development)
SAMPLE_ADMIN_PASSWORD=password
```

Lihat dokumentasi lengkap: [SEEDER-ENVIRONMENT-VARIABLES.md](./SEEDER-ENVIRONMENT-VARIABLES.md)

### Default Admin Accounts

Setelah menjalankan `php artisan migrate:fresh --seed`, akun berikut akan dibuat:

| Role             | Email                 | Password                                           | Deskripsi                     |
| :--------------- | :-------------------- | :------------------------------------------------- | :---------------------------- |
| **Super Admin**  | `admin@jepara.go.id`  | Dari `INITIAL_ADMIN_PASSWORD` atau random          | Akses penuh seluruh sistem    |
| **Admin Wisata** | `wisata@jepara.go.id` | Dari `SAMPLE_ADMIN_PASSWORD` (default: `password`) | Mengelola destinasi dan tiket |
| **Admin Berita** | `berita@jepara.go.id` | Dari `SAMPLE_ADMIN_PASSWORD` (default: `password`) | Mengelola konten berita/event |

**Penting:**

- Jika `INITIAL_ADMIN_PASSWORD` tidak di-set, password random akan di-generate dan ditampilkan di console.
- Ganti password segera setelah first login untuk keamanan.
- Jangan jalankan seeder di production tanpa environment variables yang proper.

---

## Struktur Direktori

```
e-tickets-jelajah-jepara-laravel/
|
|-- app/
|   |-- Console/Commands/          # Artisan commands (import GeoJSON, sync media, verify features)
|   |-- Http/
|   |   |-- Controllers/
|   |   |   |-- Admin/             # 15 controller admin (CRUD destinasi, tiket, event, budaya, dll)
|   |   |   |-- Auth/              # 10 controller autentikasi (login, register, Google OAuth, dll)
|   |   |   |-- Public/            # Controller publik (tiket, event, lokasi, travel agency)
|   |   |   |-- AdminController    # Dashboard & manajemen destinasi
|   |   |   |-- WelcomeController  # Landing page & halaman publik utama
|   |   |   +-- WebhookController  # Webhook handler Midtrans
|   |   |-- Middleware/            # 5 custom middleware (permission, auth, IP whitelist, security, locale)
|   |   +-- Requests/             # Form request validation
|   |-- Livewire/                  # Komponen Livewire (TicketOrders real-time)
|   |-- Models/                    # 27 Eloquent model
|   |-- Policies/                  # 6 authorization policy (RBAC granular)
|   |-- Providers/                 # Service provider
|   |-- Repositories/              # Repository pattern (contracts & implementations)
|   |-- Services/                  # 11 service class (bisnis logic)
|   +-- View/Components/           # 3 layout components (App, Guest, Public)
|
|-- config/
|   |-- features.php               # Feature flags (e-ticket, Google login)
|   |-- services.php               # Third-party service credentials
|   |-- permission.php             # Konfigurasi Spatie Permission
|   |-- purifier.php               # Konfigurasi HTML sanitizer
|   +-- livewire.php               # Konfigurasi Livewire
|
|-- database/
|   |-- migrations/                # 79 migration files
|   +-- seeders/                   # 18 seeder (admin, wisata, budaya, event, legenda, dll)
|
|-- lang/
|   |-- en.json                    # Terjemahan bahasa Inggris
|   |-- id.json                    # Terjemahan bahasa Indonesia
|   |-- en/                        # File terjemahan Laravel (EN)
|   +-- id/                        # File terjemahan Laravel (ID)
|
|-- resources/
|   |-- css/                       # Stylesheet (app.css, pages/welcome.css)
|   |-- js/                        # JavaScript (app.js, pages/welcome.js)
|   +-- views/
|       |-- admin/                 # Template dashboard admin (15 modul)
|       |-- auth/                  # Halaman login, register
|       |-- components/            # Komponen Blade reusable
|       |-- layouts/               # Layout utama (admin, guest, public)
|       |-- livewire/              # Template Livewire
|       |-- public/                # Halaman publik (home, destinasi, budaya, kuliner, dll)
|       +-- user/                  # Halaman user (booking, payment, tiket saya)
|
|-- routes/
|   |-- web.php                    # Definisi rute utama (public, admin, e-ticket)
|   +-- auth.php                   # Rute autentikasi admin (Laravel Breeze)
|
+-- public/                        # Asset statis (gambar, GeoJSON data)
```

---

## Alur Data GeoJSON

Aplikasi menggunakan data spatial GeoJSON untuk beberapa layer peta:

| Layer            | Model            | Deskripsi                               |
| :--------------- | :--------------- | :-------------------------------------- |
| Destinasi Wisata | `Place`          | Titik lokasi wisata (Point)             |
| Batas Wilayah    | `Boundary`       | Polygon batas kecamatan/desa            |
| Infrastruktur    | `Infrastructure` | Fasilitas publik (jalan, jembatan, dll) |
| Budaya           | `Culture`        | Lokasi situs budaya                     |
| Tata Guna Lahan  | `LandUse`        | Penggunaan lahan                        |

Data dikonversi dari model Eloquent ke format GeoJSON melalui `GeoJsonService` dan disajikan sebagai API endpoint JSON.

---

## Database Models

Aplikasi memiliki 27 model Eloquent:

| Model             | Deskripsi                             |
| :---------------- | :------------------------------------ |
| `User`            | User publik (wisatawan, Google OAuth) |
| `Admin`           | User admin (multi-role)               |
| `Place`           | Destinasi wisata                      |
| `PlaceImage`      | Galeri gambar destinasi               |
| `Category`        | Kategori wisata                       |
| `Ticket`          | Definisi tiket (harga, kuota)         |
| `TicketOrder`     | Pesanan tiket                         |
| `Transaction`     | Transaksi pembayaran                  |
| `Post`            | Artikel / berita                      |
| `Event`           | Agenda / event                        |
| `Culture`         | Data budaya                           |
| `CultureImage`    | Galeri gambar budaya                  |
| `CultureLocation` | Lokasi situs budaya (GeoJSON)         |
| `Legend`          | Legenda daerah                        |
| `Announcement`    | Pengumuman                            |
| `Media`           | Media umum (hero, galeri)             |
| `Boundary`        | Batas wilayah (polygon)               |
| `Infrastructure`  | Data infrastruktur                    |
| `LandUse`         | Tata guna lahan                       |
| `Population`      | Data demografi                        |
| `TourismStat`     | Statistik pariwisata                  |
| `Visit`           | Log kunjungan                         |
| `TravelAgency`    | Agen perjalanan                       |
| `HeroSetting`     | Pengaturan hero section               |
| `FooterSetting`   | Pengaturan footer                     |
| `ActivityLog`     | Log aktivitas admin                   |
| `WebhookLog`      | Log webhook Midtrans                  |

---

## Testing

Aplikasi menggunakan **Pest PHP v4** sebagai testing framework:

```bash
composer run test
```

---

## Deployment Notes

- Set `APP_ENV=production` dan `APP_DEBUG=false` di `.env`.
- Jalankan `npm run build` untuk build asset production.
- Pastikan `MIDTRANS_IS_PRODUCTION=true` jika menggunakan fitur e-ticket di production.
- Konfigurasi `APP_URL` dengan domain HTTPS.
- Set `SESSION_SECURE_COOKIE=true` untuk production.
- Jalankan `php artisan config:cache`, `php artisan route:cache`, dan `php artisan view:cache` untuk optimasi.
- Pastikan queue worker berjalan (`php artisan queue:work`) untuk proses background.

---

**Dikembangkan oleh Mahasiswa Magang Unisnu Jepara - Teknik Informatika**
_Bekerja sama dengan Dinas Pariwisata dan Kebudayaan Kabupaten Jepara_

(c) 2026 **Jelajah Jepara**. All Rights Reserved.
