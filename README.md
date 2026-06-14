# PORTO SPACE - Orbital Logistics & Fleet Telemetry

Porto Space adalah platform logistik luar angkasa dinamis berbasis **PHP Procedural & MySQLi**. Platform ini dirancang untuk mendigitalisasi pengajuan kargo satelit (dari klien publik) dan menyediakan pusat komando rahasia bagi admin untuk mengelola pelacakan armada pesawat luar angkasa (OTV) secara *real-time*.

---

## 📌 Daftar Isi
*   [Teknologi yang Digunakan](#teknologi-yang-digunakan)
*   [Fitur Utama](#fitur-utama)
*   [Struktur Database](#struktur-database)
*   [Struktur File](#struktur-file)
*   [Alur Pengguna](#alur-pengguna)
*   [Cara Menjalankan](#cara-menjalankan)

---

## 💻 Teknologi yang Digunakan

Aplikasi web Porto Space dibangun dengan integrasi teknologi berikut:

*   **PHP (v8.2.4):** Bahasa pemrograman backend utama (prosedural & MySQLi) untuk memproses semua kueri, sesi, dan logika validasi server.
*   **MySQL / MariaDB:** Database relasional untuk menyimpan data manifest kargo, registrasi armada roket, dan akun administrator.
*   **HTML5 & CSS3:** Kerangka dasar antarmuka web dan kustomisasi visual dengan gaya *cyber-industrial dark mode*.
*   **JavaScript (ES6+):** Digunakan untuk mengelola interaktivitas klien, animasi penghitung metrik (*metric counter*), dan deteksi aktivitas fisik (*idle timeout*).
*   **Bootstrap (v5.3.0):** Framework CSS responsif untuk mempercepat penyusunan tata letak halaman yang ramah seluler.
*   **Chart.js (v4.x):** Pustaka visualisasi grafik interaktif untuk memproyeksikan diagram matriks armada dan kargo.
*   **Bootstrap Icons:** Pustaka ikon grafis untuk memperkuat tampilan telemetry dan HUD.

---

## 🚀 Fitur Utama

### Pengguna Umum (Klien/Publik)
*   **Homepage & Informasi:** Menampilkan profil perusahaan, armada, dan metrik bisnis secara visual.
*   **Requisition Form (`contact.php`):** Formulir pengajuan manifest kargo luar angkasa. Data yang masuk akan secara otomatis tersimpan di database dengan status `PENDING`.
*   **Live Tracking (`fleet.php`):** Menampilkan posisi *real-time* dari unit roket pengantar.
*   **Active Manifest (`services.php`):** Menampilkan daftar kargo yang sudah disetujui (`ACTIVE`).
*   **Telemetry Tracker (`track.php`):** Mesin pelacak pintar dengan fitur filter dropdown. Klien dapat mengklik tombol "Readout" untuk membuka *Pop-up Modal* berisi detail kargo dan posisi armada secara interaktif (Menggunakan SQL `LEFT JOIN`).

### Pengelola Operasional (Admin / Dispatcher)
*   **Secure Login (`login.php`):** Menggunakan autentikasi *PHP Sessions*.
*   **Auto-Kick Security:** Jika admin diam selama 10 menit, sistem akan memicu *idle-timeout* dan otomatis menghancurkan sesi untuk mencegah pembajakan konsol.
*   **Operations Console (`dashboard.php`):**
    *   Menampilkan seluruh kargo publik dengan sistem *Pagination* dan *Multi-Filter*.
    *   **Pipa Persetujuan (Approval Pipeline):** Mengubah status kargo dari `PENDING` menjadi `ACTIVE` dengan menetapkan kapal peluncur (Assign OTV).
    *   **Auto-Launch Relasional:** Saat kapal ditugaskan, sistem otomatis memperbarui lokasi kapal tersebut di tabel armada (Fleet) tanpa perlu input manual ganda.
    *   **Full CRUD:** Tambah, Baca, Perbarui, dan Hapus data kargo maupun armada kapal.
*   **Tactical HUD (`stats.php`):** Visualisasi data *real-time* menggunakan **Chart.js** (Doughnut, Bar, dan Stepped Line Chart) beserta animasi Terminal Feed interaktif.

---

## 🗄️ Struktur Database

Sistem ini didukung oleh database **`db_portospace`** yang terdiri dari 3 tabel utama:

### 1. Tabel Admin (`tb_admins`)
Menyimpan data akun administrator/flight operator yang diotorisasi untuk masuk ke konsol kendali.
*   `id` (INT, Primary Key, Auto Increment)
*   `username` (VARCHAR) - Nama pengguna untuk masuk sistem.
*   `password` (VARCHAR) - Kata sandi dalam format teks polos.
*   `created_at` (TIMESTAMP)

### 2. Tabel Manifest (`tb_missions`)
Menyimpan seluruh draf manifest kargo dan spesifikasi orbit satelit yang diajukan oleh klien.
*   `id` (INT, Primary Key, Auto Increment)
*   `consignor` (VARCHAR) - Nama perusahaan/klien pemilik kargo.
*   `email` (VARCHAR) - Alamat email perwakilan resmi klien.
*   `target_orbit` (VARCHAR) - Koordinat/vektor orbit tujuan.
*   `parameters` (TEXT) - Spesifikasi teknis kargo satelit.
*   `mission_status` (VARCHAR) - Status pengajuan kargo (`PENDING` atau `ACTIVE`).
*   `assigned_vessel` (VARCHAR) - Kapal pengantar OTV yang ditugaskan (Default: `UNASSIGNED`).
*   `created_at` (TIMESTAMP)

### 3. Tabel Telemetry (`tb_fleet`)
Menyimpan posisi koordinat terkini dan data telemetry real-time dari unit armada fisik pesawat luar angkasa.
*   `id` (INT, Primary Key, Auto Increment)
*   `vessel_name` (VARCHAR) - Nama fisik unik unit roket (e.g. ATLAS-01).
*   `location` (VARCHAR) - Koordinat lokasi fisik terkini kapal di orbit.
*   `eta` (VARCHAR) - Estimasi waktu kedatangan di orbit tujuan.
*   `status_text` (VARCHAR) - Kode teks status telemetri (e.g. IN TRANSIT).
*   `status_type` (VARCHAR) - Tanda marker visual CSS (`transit` / `docked`).

---

## 📁 Struktur File

```text
porto-space/
├── index.php             → Halaman beranda utama dengan video background
├── about.php             → Halaman profil perusahaan & kru komando
├── services.php          → Halaman kapabilitas & log Active Manifest
├── fleet.php             → Halaman daftar armada & Live Telemetry Tracking
├── track.php             → Halaman pencarian kargo dengan Pop-up Modal Detail
├── contact.php           → Form pengajuan kargo (Masuk ke database sbg PENDING)
├── terms.php             → Halaman legalitas (Terms of Space Carriage)
├── compliance.php        → Halaman kepatuhan (Space Debris Mitigation)
│
├── navbar.php            → Template menu navigasi utama (Auto-active highlight)
├── footer.php            → Template footer utama (Terdapat link rahasia ke Login)
├── koneksidb.php         → Konfigurasi kredensial & koneksi database MySQL
├── style.css             → Stylesheet utama (Tema Cyber-Industrial Dark Mode)
│
├── login.php             → Halaman autentikasi akses Admin Terminal
├── dashboard.php         → Command Center Admin (CRUD, Pagination, Multi-Filter)
├── stats.php             → Tactical HUD Analytics (Visualisasi Chart.js & Terminal)
├── logout.php            → Script keamanan untuk menghancurkan sesi admin
│
├── db_portospace.sql     → Skrip blueprint Database (Struktur tabel & Dummy Data)
└── assets/               → Direktori file media (Background, Ikon, Gambar Kapal)
```

---

## 🗺️ Alur Pengguna

```text
Beranda (index.php)
├── Gateway Publik
│   ├── About Us (about.php)
│   ├── Services (services.php) ──> Menampilkan kargo berstatus [ACTIVE]
│   ├── Fleet (fleet.php)       ──> Menampilkan lokasi roket secara [Real-Time]
│   ├── Tracker (track.php)     ──> Pencarian Kargo ──> Pop-up Telemetry Readout
│   └── Contact (contact.php)   ──> Submit Kargo ──> Masuk Database [PENDING]
│
└── Gateway Internal (Via Footer)
    └── Terminal Login (login.php)
        └── Autentikasi Sesi & Keamanan
            ├── Operations Console (dashboard.php)
            │   ├── Approve Kargo ──────────> Assign OTV ──> Kargo menjadi [ACTIVE]
            │   ├── Update Telemetry ───────> Perbarui lokasi & ETA Kapal
            │   └── Manage Registry ────────> Tambah / Hapus kargo & unit armada
            │
            └── Tactical HUD (stats.php)
                └── Visualisasi Data ───────> Menampilkan grafik matriks operasional
```

---

## ⚙️ Cara Menjalankan

1. **Clone Repository:**
   Unduh file ZIP atau jalankan `git clone` ke dalam folder *local server* Anda:
   * Windows: `C:\xampp\htdocs\porto-space`
   * Mac: `/Applications/XAMPP/xamppfiles/htdocs/porto-space`

2. **Jalankan Local Server:**
   Buka aplikasi **XAMPP**, lalu klik *Start* pada modul **Apache** dan **MySQL**.

3. **Import Database:**
   * Buka browser dan ketik: `http://localhost/phpmyadmin/`
   * Buat database baru bernama: `db_portospace`
   * Klik database tersebut, pilih tab **Import**.
   * Unggah file **`db_portospace.sql`** yang ada di folder project ini, lalu klik Go.

4. **Akses Website:**
   * **Halaman Publik:** `http://localhost/porto-space/index.php`
   * **Halaman Admin:** `http://localhost/porto-space/login.php`
   * **Kredensial Admin:** Username: `admin` | Password: `aidanganteng`
```