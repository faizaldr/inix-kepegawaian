# AI PROMPT: Application Generator — Aplikasi Kepegawaian PT INIX (PHP Native Monolitik + MySQL & Eduparx Theme)

## Tujuan

Prompt RISEN ini digunakan untuk meminta AI (ChatGPT / Claude / Gemini) membuat **source code lengkap** aplikasi Kepegawaian & Pelatihan PT INIX berbasis **Arsitektur Monolitik PHP Native (PDO MySQL, SSR MVC)** dengan tema visual modern menyerupai **Eduparx Training Catalog** ([https://eduparx.id/home/training?catalog=ai-engineer](https://eduparx.id/home/training?catalog=ai-engineer)).

Aplikasi ini dibangun dalam **satu kesatuan monolitik (Server-Side Rendered)** tanpa dependensi framework (No Laravel/Symfony), siap di-deploy menggunakan Docker (`php:8.2-apache` + `mysql:8.0`), dan **dirancang khusus agar bisa diuji oleh seluruh tools testing** dalam kurikulum pelatihan (Chrome DevTools, Postman, DBeaver, Playwright, k6, OWASP ZAP, SonarQube, Semgrep, GitHub Actions).

> **⚠️ PENTING — AI dengan Context Window Pendek (~128 token remembering)**
>
> Prompt ini dipecah menjadi **30 step atomik**. Setiap step dirancang untuk bisa dieksekusi **mandiri** oleh AI tanpa perlu mengingat step sebelumnya. Setiap step menyertakan konteks minimal yang dibutuhkan.
>
> **Cara penggunaan:**
> 1. Jalankan setiap step **satu per satu** secara berurutan
> 2. Simpan output setiap step ke file yang ditunjuk
> 3. Jika AI "lupa", cukup paste ulang step yang sedang dikerjakan — setiap step **self-contained**

---

## Peta Tools Testing ↔ Fitur Aplikasi Monolitik yang Harus Mendukung

Sebelum membuat aplikasi, pastikan setiap fitur/konfigurasi mendukung tools berikut:

| Kategori | Tools | Fitur Aplikasi Monolitik yang Harus Ada |
|---|---|---|
| **Manual & Management** | Chrome DevTools | Network tab menampilkan request/response dokumen HTML, asset CSS/JS, dan POST form; Console bersih tanpa unhandled JS error; Application tab menyimpan PHP Session Cookie (`PHPSESSID`); Elements memiliki semantic HTML lengkap |
| | Postman | Testing request form-urlencoded & multipart ke endpoint monolitik; Cookie Jar otomatis menyimpan session login; status code akurat (200, 302 Redirect, 400, 403, 404) |
| | DBeaver / SQL Client | Port MySQL 3306 terbuka; DDL `schema.sql` dan seeder `seed.php` membuat relasi FK antar tabel dengan presisi untuk verifikasi langsung ke database |
| | Jira / TestRail / Qase | Validasi input server-side, pesan error spesifik, dan skenario RBAC role untuk pencatatan test case dan bug reporting |
| **Automation & Coding** | Playwright | Seluruh elemen interaktif memiliki atribut `data-testid`; form label terhubung ke ID input (`for`/`id`); tombol memiliki accessible name; navigasi Server-Side Rendered yang stabil dan mudah di-assert |
| | PHP / Static Analysis | Struktur monolitik modular MVC bersih; PDO prepared statements konsisten tanpa query concat |
| | Allure Report | Kompatibel dengan test runner Playwright / Postman Newman |
| **Performance & Security** | k6 | Uji beban rendering halaman monolitik dan throughput POST login berbasis session |
| | OWASP ZAP | Missing security headers sengaja dibiarkan terbuka (`X-Powered-By: PHP/8.2` aktif, tanpa CSP ketat); form HTML mudah di-crawl dan di-fuzzing |
| | SonarQube / Semgrep | Source code monolitik mudah di-scan dalam satu root directory |
| **CI/CD** | GitHub Actions | Dockerfile dan docker-compose.yml monolitik lengkap; CLI seeder otomatis; healthcheck endpoint |
| | Docker | Single container aplikasi PHP-Apache terhubung ke container database MySQL |
| **Frontend Testing** | Lighthouse / axe DevTools | Semantic HTML (`<header>`, `<nav>`, `<main>`, `<aside>`, `<footer>`, `<table>`); contrast ratio warna memadai; ARIA labels; meta viewport |
| | Responsive Testing | Layout responsif (desktop, tablet, mobile); flexbox & CSS grid murni |

---

## PROMPT RISEN — Application Generator

---

**[R] ROLE:**

Bertindaklah sebagai **Principal Full-Stack PHP Engineer & UI/UX Specialist** yang membangun aplikasi web monolitik untuk kebutuhan **pelatihan Software Testing**. Anda memahami bahwa aplikasi ini akan digunakan sebagai bahan latihan oleh QA Engineer yang menguji menggunakan: Chrome DevTools, Postman, DBeaver, Playwright, k6, OWASP ZAP, SonarQube, Semgrep, GitHub Actions, dan Docker.

Aplikasi mengusung identitas visual **Eduparx by Inixindo** (mengacu pada halaman web `https://eduparx.id/home/training?catalog=ai-engineer`).

Aplikasi harus memenuhi:
1. **Arsitektur Monolitik PHP Native Murni** — Seluruh logika bisnis, routing, database model, dan tampilan HTML (Server-Side Rendered Views) berada dalam satu project monolitik berbasis PHP 8.2+. Tidak ada pemisahan frontend framework atau SPA yang terpisah.
2. **Eduparx Visual Style** — Top navigation bar dengan logo PT Inixindo / Eduparx, Training Hero Banner dengan gradient biru, Breadcrumb, Catalog Sidebar Filter di sisi kiri, Card Grid interaktif bernuansa kursus IT/AI di sisi kanan, serta Multi-column Footer resmi Inixindo.
3. **Fully testable** — setiap elemen interaktif memiliki `data-testid`, accessible label, semantic markup, dan opsi View Toggle (Card Grid ↔ Data Table) agar automation test Playwright dan manual test tetap 100% presisi.
4. **Session-Based Authentication** — Menggunakan mekanisme autentikasi bawaan PHP Native Session (`$_SESSION`) dengan cookie session standar, dilengkapi proteksi CSRF token pada setiap form POST.
5. **Dockerized Monolith** — docker-compose one-command setup (`docker-compose up -d`) berisi container aplikasi monolitik (PHP 8.2 Apache) dan database MySQL 8.0.
6. **Security-aware** — beberapa security headers sengaja dibiarkan default/missing agar ZAP bisa mendeteksi vulnerability findings untuk latihan.

---

**[I] INPUT:**

Buat aplikasi **Kepegawaian & Pelatihan PT INIX (Monolitik PHP Native + MySQL, Eduparx Theme)** dengan spesifikasi berikut:

### Tech Stack Monolitik

| Komponen | Teknologi | Versi |
|---|---|---|
| Arsitektur | Monolitik MVC (Front Controller + Model-View-Controller) | PHP Native |
| Backend & Server | PHP Native + Apache dengan `mod_rewrite` | PHP 8.2+, Apache 2.4 |
| Database | MySQL | 8.0 (InnoDB, UTF8mb4) |
| Database Access | PHP Data Objects (PDO) Prepared Statements | Native PDO |
| Template & Views | Server-Side Rendered (PHP Views / Partial Layouts) | HTML5 |
| Frontend Assets | Pure CSS (Eduparx Design System) + Vanilla JavaScript | ES6 / CSS3 |
| Autentikasi | PHP Native Session (`$_SESSION`) + `password_hash()` | Cookie Session |
| Keamanan Dasar | CSRF Token per session + Sanitasi Output XSS `htmlspecialchars` | Native |
| Containerization | Docker + Docker Compose | PHP 8.2-Apache & MySQL 8.0 |

---

### Desain & Tema UI (Eduparx Training Catalog Theme)

Mengacu langsung pada layout dan komponen `https://eduparx.id/home/training?catalog=ai-engineer`:

1. **Top Navbar (Header Eduparx)**:
   - Logo: **INIXINDO | Eduparx**
   - Nav items: *Home*, *Katalog Training*, *Data Pegawai*, *Pengajuan Cuti*, *Dashboard*
   - Status login: Nama user dengan role badge + Button *Logout* (atau link *Masuk* saat belum login)
2. **Training Hero / Banner Section (`training-page-header`)**:
   - Background gradient elegan: linier dari `#1b2a4e` ke `#204ecf`
   - Breadcrumb navigasi: `Home > Training Catalog > AI Engineer & Kepegawaian`
   - Title: `Katalog Pelatihan & Kepegawaian PT INIX`
   - Subtitle: `Kelola data talenta, pengembangan kompetensi AI Engineer, dan pengajuan cuti pegawai internal PT Inixindo.`
   - Highlight Meta Pills:
     - `⭐ 4.9 (520+ Ulasan)`
     - `⏱️ 4 Hari Pelatihan (32 JP)`
     - `🎯 Level: Foundation to Advanced`
     - `💼 Inixindo Certified Professional`
3. **Catalog Layout 2-Kolom (Filter Sidebar + Content Area)**:
   - **Sidebar Filter Kiri (`catalog-filter-box`)**:
     - Input pencarian cepat pegawai/training
     - Kategori Katalog: *AI Engineer*, *Software Testing & QA*, *Cloud & DevOps*, *Cyber Security*, *HR & Management*
     - Filter Jabatan / Status: *Aktif*, *Non-Aktif*
     - Tombol *Reset Filter*
   - **Area Konten Kanan**:
     - Bar atas: Counter hasil (*"Menampilkan 5 Data"*), View Toggle button (Card Grid View ↔ Data Table View)
     - **Card Grid View (Eduparx Course Card Style)**:
       - Header thumbnail / avatar dengan gradient badge kategori
       - Nama pegawai / course title dengan tipografi tegas (`#262d3d`)
       - Metadata rows: NIP, Jabatan/Role, Status badge (*Aktif / Nonaktif*)
       - Action buttons: *Edit*, *Hapus* (dengan `data-testid` masing-masing)
     - **Data Table View**: Tabel semantic HTML lengkap dengan `data-testid` untuk pengujian test automation Playwright & SQL verification.
4. **Eduparx Multi-Column Footer**:
   - Kolom 1: Profil PT Inixindo & Eduparx — Solusi Pelatihan & Transformasi Digital.
   - Kolom 2: Program Unggulan: *AI Engineer Program*, *Software Quality Assurance*, *Cloud Computing*, *Cyber Defense*.
   - Kolom 3: Kantor Resmi: Jl. Kenari No. 69 Yogyakarta, Gd. Permata Kuningan Jakarta, Email: `support@eduparx.id`.
   - Bottom bar: `© 2026 PT Inixindo (Eduparx). Seluruh Hak Cipta Dilindungi.`

---

### 3 Modul Fitur Aplikasi Monolitik

**Modul 1: Autentikasi & Session**
- Form Login: GET `/login`
- Proses Login: POST `/login` — validasi username & password, verifikasi `password_verify()`, simpan `$_SESSION['user']`, redirect ke `/dashboard`
- Logout: GET `/logout` atau POST `/logout` — destroy session, redirect ke `/login`
- RBAC: 4 role — Admin, HR, Manajer, Pegawai

**Modul 2: Data Pegawai (CRUD Monolitik)**
- GET `/pegawai` — katalog pegawai (mendukung query string `search`, `status`, `kategori`, render view katalog 2-kolom)
- GET `/pegawai/tambah` — render form tambah pegawai (khusus role HR & Admin)
- POST `/pegawai/simpan` — proses simpan data baru dengan validasi NIP unik, redirect ke `/pegawai` dengan flash message
- GET `/pegawai/edit?id={id}` — render form edit data pegawai
- POST `/pegawai/update` — proses update data pegawai, redirect ke `/pegawai`
- POST `/pegawai/hapus` — proses hapus data pegawai (khusus role Admin), redirect ke `/pegawai`
- Field: `id`, `user_id`, `nip` (UNIQUE), `nama_lengkap`, `jabatan`, `status` (`aktif`/`nonaktif`), `kuota_cuti`

**Modul 3: Pengajuan Cuti (Monolitik)**
- GET `/cuti` — list cuti (Pegawai: melihat milik sendiri & ringkasan sisa kuota; Manajer/HR/Admin: melihat seluruh pengajuan)
- GET `/cuti/ajukan` — form pengajuan cuti (khusus role Pegawai)
- POST `/cuti/simpan` — validasi tgl_mulai, tgl_selesai, dan kuota cuti tahunan (default 12 hari), redirect ke `/cuti`
- POST `/cuti/status` — approve/reject status PENDING (khusus role Manajer), redirect ke `/cuti`
- Field: `id`, `pegawai_id`, `tgl_mulai`, `tgl_selesai`, `alasan`, `status` (`PENDING`/`APPROVED`/`REJECTED`)

---

### Database Schema (MySQL)

```sql
CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(100) UNIQUE NOT NULL,
  password VARCHAR(255) NOT NULL,
  role ENUM('admin','hr','manajer','pegawai') NOT NULL DEFAULT 'pegawai',
  is_active BOOLEAN DEFAULT TRUE,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS pegawai (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NULL,
  nip VARCHAR(50) UNIQUE NOT NULL,
  nama_lengkap VARCHAR(200) NOT NULL,
  jabatan VARCHAR(100) NOT NULL,
  status ENUM('aktif','nonaktif') DEFAULT 'aktif',
  kuota_cuti INT NOT NULL DEFAULT 12,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS cuti (
  id INT AUTO_INCREMENT PRIMARY KEY,
  pegawai_id INT NOT NULL,
  tgl_mulai DATE NOT NULL,
  tgl_selesai DATE NOT NULL,
  alasan TEXT NOT NULL,
  status ENUM('PENDING','APPROVED','REJECTED') DEFAULT 'PENDING',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (pegawai_id) REFERENCES pegawai(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

### Seed Data

```
Users:
  admin@inix.com    / Admin@123!     / role: admin
  hr@inix.com       / Hr@123!        / role: hr
  manager@inix.com  / Manager@123!   / role: manajer
  pegawai@inix.com  / Pegawai@123!   / role: pegawai

Pegawai:
  NIP-2025-001 / Andi Wijaya    / Staff HR            / aktif    / user: hr@inix.com
  NIP-2025-002 / Siti Rahayu    / Manajer IT          / aktif    / user: manager@inix.com
  NIP-2025-003 / Rudi Hermawan  / Lead QA Automation  / nonaktif / user: NULL
  NIP-2025-004 / Budi Santoso   / AI Engineer         / aktif    / user: pegawai@inix.com
  NIP-2025-005 / Maya Putri     / Staff Data Science  / aktif    / user: NULL

Cuti (sample):
  pegawai_id=4, 2026-12-01 s.d 2026-12-03 (3 hari), "Mengikuti Konferensi AI Inixindo", PENDING
  pegawai_id=5, 2026-11-20 s.d 2026-11-21 (2 hari), "Sakit",                          APPROVED
```

---

### Halaman Web Monolitik (Eduparx Design)

| No | Halaman | Route | Deskripsi & Desain Eduparx | Elemen UI & `data-testid` Penting |
|---|---|---|---|---|
| 1 | **Login** | `/login` | Form login dengan card container Eduparx modern + logo Inixindo | Input username (`data-testid="input-username"`), Input password (`data-testid="input-password"`), Button "Login" (`data-testid="btn-login"`), Error alert (`data-testid="error-message"`) |
| 2 | **Dashboard** | `/dashboard` | Dashboard LMS/Kepegawaian dengan Hero Banner + Summary Metric Cards | Welcome message (`data-testid="welcome-msg"`), Quick action cards, Stat counter jumlah pegawai & cuti pending |
| 3 | **Katalog & Data Pegawai** | `/pegawai` | Layout 2-kolom Eduparx: Filter Sidebar di kiri + Card Grid/Table toggle di kanan | Table (`data-testid="pegawai-table"`), Search input (`data-testid="search-pegawai"`), Button "Tambah Pegawai" (`data-testid="btn-tambah"`), Edit (`data-testid="btn-edit-{id}"`), Delete (`data-testid="btn-delete-{id}"`), View toggle button |
| 4 | **Form Pegawai** | `/pegawai/tambah` atau `/pegawai/edit?id={id}` | Form input berbingkai card Eduparx dengan label terhubung rapi | NIP (`data-testid="input-nip"`), Nama (`data-testid="input-nama"`), Jabatan (`data-testid="input-jabatan"`), Status (`data-testid="select-status"`), Simpan (`data-testid="btn-simpan"`), Batal (`data-testid="btn-batal"`), Alert (`data-testid="success-message"`, `data-testid="form-error"`) |
| 5 | **Pengajuan Cuti — List** | `/cuti` | Tabel status cuti dengan badge warna Eduparx (Pending kuning, Approved hijau, Rejected merah) | Table (`data-testid="cuti-table"`), Button "Ajukan Cuti" (`data-testid="btn-ajukan-cuti"`), Kuota Tahunan (`data-testid="kuota-tahunan"`), Sisa Kuota (`data-testid="sisa-kuota-cuti"`), Approve (`data-testid="btn-approve-{id}"`), Reject (`data-testid="btn-reject-{id}"`) |
| 6 | **Pengajuan Cuti — Form** | `/cuti/ajukan` | Form pengajuan cuti dengan validasi tanggal dan feedback error | Tgl Mulai (`data-testid="input-tgl-mulai"`), Tgl Selesai (`data-testid="input-tgl-selesai"`), Alasan (`data-testid="input-alasan"`), Submit (`data-testid="btn-submit-cuti"`), Durasi (`data-testid="durasi-cuti"`), Error (`data-testid="validation-error"`) |

---

**[S] STEPS — 30 Step Atomik (Satu Step = Satu Prompt ke AI)**

> **Petunjuk Penggunaan:**
> - Eksekusi **satu step per giliran**.
> - Simpan hasil ke file sesuai path yang tertera.
> - Setiap step mandiri (*self-contained*), cocok untuk model AI dengan memory terbatas.

---

### FASE A: STRUKTUR MONOLITIK & KONFIGURASI (Step 1–4)

---

#### Step 1 — Buat File Root: .gitignore & .env.example

```
Buat folder project monolitik "inix-kepegawaian" dengan 2 file konfigurasi dasar:

1. File: .gitignore
   Isi:
   .env
   mysql_data/
   *.log
   .DS_Store

2. File: .env.example
   Isi:
   DB_HOST=db
   DB_PORT=3306
   DB_NAME=inix_db
   DB_USER=root
   DB_PASSWORD=rootpassword
   APP_ENV=development
   APP_KEY=inix-secret-key-testing-2026

Berikan isi lengkap kedua file tersebut.
```

**Output:** `.gitignore`, `.env.example`

---

#### Step 2 — File: .env (Konfigurasi Aplikasi Monolitik)

```
Buat file .env di root project inix-kepegawaian/.

Isi:
DB_HOST=db
DB_PORT=3306
DB_NAME=inix_db
DB_USER=root
DB_PASSWORD=rootpassword
APP_ENV=development
APP_KEY=inix-secret-key-testing-2026

Berikan isi lengkap file .env.
```

**Output:** `.env`

---

#### Step 3 — Buat Struktur Folder Monolitik MVC

```
Di dalam folder inix-kepegawaian/, siapkan struktur folder monolitik MVC berikut:

inix-kepegawaian/
├── config/
│   └── database.php
├── database/
│   ├── schema.sql
│   └── seed.php
├── includes/
│   ├── helpers.php
│   ├── auth.php
│   └── Router.php
├── app/
│   ├── Models/
│   │   ├── User.php
│   │   ├── Pegawai.php
│   │   └── Cuti.php
│   ├── Controllers/
│   │   ├── AuthController.php
│   │   ├── DashboardController.php
│   │   ├── PegawaiController.php
│   │   └── CutiController.php
│   └── Views/
│       ├── layouts/
│       │   ├── header.php
│       │   ├── footer.php
│       │   └── confirm_dialog.php
│       ├── auth/
│       │   └── login.php
│       ├── dashboard/
│       │   └── index.php
│       ├── pegawai/
│       │   ├── index.php
│       │   └── form.php
│       └── cuti/
│           ├── index.php
│           └── form.php
├── public/
│   ├── index.php
│   ├── .htaccess
│   └── assets/
│       ├── css/
│       │   └── style.css
│       └── js/
│           └── app.js
├── Dockerfile
├── docker-compose.yml
└── README.md

Berikan daftar perintah terminal Windows / CMD untuk membuat seluruh folder tersebut.
```

**Output:** Struktur folder monolitik dan command CMD pembuatan direktori

---

#### Step 4 — File: config/database.php (PDO Singleton)

```
Buat file config/database.php untuk koneksi database MySQL monolitik menggunakan PHP PDO.

Spesifikasi:
- Class Database dengan static method `getConnection(): PDO`
- Membaca konfigurasi dari environment variable atau file .env (fallback default: host=db, port=3306, dbname=inix_db, user=root, password=rootpassword)
- Opsi PDO wajib:
  - PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
  - PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
  - PDO::ATTR_EMULATE_PREPARES => false
  - PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"
- Tangani PDOException dengan pesan aman dan log error.

Berikan isi lengkap file config/database.php.
```

**Output:** `config/database.php`

---

### FASE B: DATABASE & DATA SEEDER (Step 5–6)

---

#### Step 5 — File: database/schema.sql

```
Buat file database/schema.sql berisi DDL tabel-tabel MySQL aplikasi monolitik:

Spesifikasi:
1. Tabel users:
   - id INT AUTO_INCREMENT PRIMARY KEY
   - username VARCHAR(100) UNIQUE NOT NULL
   - password VARCHAR(255) NOT NULL
   - role ENUM('admin','hr','manajer','pegawai') NOT NULL DEFAULT 'pegawai'
   - is_active BOOLEAN DEFAULT TRUE
   - created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
   - updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP

2. Tabel pegawai:
   - id INT AUTO_INCREMENT PRIMARY KEY
   - user_id INT NULL
   - nip VARCHAR(50) UNIQUE NOT NULL
   - nama_lengkap VARCHAR(200) NOT NULL
   - jabatan VARCHAR(100) NOT NULL
   - status ENUM('aktif','nonaktif') DEFAULT 'aktif'
   - kuota_cuti INT NOT NULL DEFAULT 12
   - created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
   - updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
   - FK (user_id) REFERENCES users(id) ON DELETE SET NULL

3. Tabel cuti:
   - id INT AUTO_INCREMENT PRIMARY KEY
   - pegawai_id INT NOT NULL
   - tgl_mulai DATE NOT NULL
   - tgl_selesai DATE NOT NULL
   - alasan TEXT NOT NULL
   - status ENUM('PENDING','APPROVED','REJECTED') DEFAULT 'PENDING'
   - created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
   - updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
   - FK (pegawai_id) REFERENCES pegawai(id) ON DELETE CASCADE

Berikan isi lengkap file database/schema.sql.
```

**Output:** `database/schema.sql`

---

#### Step 6 — File: database/seed.php (CLI Seeder Data Awal)

```
Buat file database/seed.php yang dieksekusi via CLI (`php database/seed.php`) untuk me-reset dan mengisi database testing:

Spesifikasi:
1. Jalankan schema.sql untuk memastikan tabel fresh.
2. Insert 4 users dengan password_hash(..., PASSWORD_DEFAULT):
   - admin@inix.com    / Admin@123!   / role: admin
   - hr@inix.com       / Hr@123!      / role: hr
   - manager@inix.com  / Manager@123! / role: manajer
   - pegawai@inix.com  / Pegawai@123! / role: pegawai
3. Insert 5 pegawai PT INIX:
   - NIP-2025-001 / Andi Wijaya   / Staff HR           / aktif    / user hr@inix.com
   - NIP-2025-002 / Siti Rahayu   / Manajer IT         / aktif    / user manager@inix.com
   - NIP-2025-003 / Rudi Hermawan / Lead QA Automation / nonaktif / user NULL
   - NIP-2025-004 / Budi Santoso  / AI Engineer        / aktif    / user pegawai@inix.com
   - NIP-2025-005 / Maya Putri    / Staff Data Science / aktif    / user NULL
4. Insert 2 sample pengajuan cuti:
   - pegawai_id = 4, mulai: 2026-12-01, selesai: 2026-12-03, alasan: "Mengikuti Konferensi AI Inixindo", status: PENDING
   - pegawai_id = 5, mulai: 2026-11-20, selesai: 2026-11-21, alasan: "Sakit", status: APPROVED
5. Cetak output terminal: "[SUCCESS] Inix database seeded successfully."

Berikan isi lengkap file database/seed.php.
```

**Output:** `database/seed.php`

---

### FASE C: HELPERS & AUTENTIKASI SESSION (Step 7–8)

---

#### Step 7 — File: includes/helpers.php

```
Buat file includes/helpers.php berisi fungsi bantuan aplikasi monolitik:

Fungsi yang wajib ada:
1. `e($str)`: sanitasi output XSS via htmlspecialchars($str ?? '', ENT_QUOTES, 'UTF-8')
2. `csrf_token()`: generate random token di session jika belum ada, kembalikan string token
3. `csrf_field()`: cetak tag HTML `<input type="hidden" name="_csrf" value="...">`
4. `csrf_verify()`: validasi `$_POST['_csrf'] === $_SESSION['_csrf']`, batalkan request jika tidak valid
5. `flash_set($key, $message)`: simpan notifikasi alert di session (`$_SESSION['flash'][$key] = $message`)
6. `flash_get($key)`: ambil dan hapus pesan flash dari session
7. `redirect($url)`: kirim header redirect Location dan panggil exit

Berikan isi lengkap file includes/helpers.php.
```

**Output:** `includes/helpers.php`

---

#### Step 8 — File: includes/auth.php (Session Guard & RBAC)

```
Buat file includes/auth.php untuk pengelolaan autentikasi berbasis PHP Native Session:

Fungsi:
1. `auth_user()`: mengembalikan array session user saat ini (`$_SESSION['user'] ?? null`)
2. `auth_check()`: mengembalikan boolean true jika user sudah login di session
3. `require_auth()`: jika `!auth_check()`, simpan URL tujuan ke session, arahkan ke `/login`
4. `require_role(...$allowedRoles)`: panggil `require_auth()`; jika role user tidak terdaftar dalam $allowedRoles, tampilkan halaman 403 Forbidden atau redirect ke `/dashboard` dengan flash message error
5. `auth_login($user)`: simpan `$_SESSION['user'] = ['id' => $user['id'], 'username' => $user['username'], 'role' => $user['role']]`
6. `auth_logout()`: unset session user, destroy session data, dan regenerate session id

Berikan isi lengkap file includes/auth.php.
```

**Output:** `includes/auth.php`

---

### FASE D: MODELS (Step 9–11)

---

#### Step 9 — File: app/Models/User.php

```
Buat file app/Models/User.php — Model User monolitik dengan PDO:

Methods:
1. `findByUsername($username)`: SELECT * FROM users WHERE username = ? LIMIT 1
2. `findById($id)`: SELECT id, username, role, is_active FROM users WHERE id = ? LIMIT 1
3. `getAll()`: SELECT id, username, role, is_active FROM users ORDER BY id ASC

Berikan isi lengkap file app/Models/User.php.
```

**Output:** `app/Models/User.php`

---

#### Step 10 — File: app/Models/Pegawai.php

```
Buat file app/Models/Pegawai.php — Model Pegawai monolitik dengan PDO:

Methods:
1. `getAll($search = '', $status = '', $jabatan = '')`:
   - SELECT p.*, u.username as user_username FROM pegawai p LEFT JOIN users u ON p.user_id = u.id
   - Filter query jika ada parameter search (LIKE nama/nip), filter status, filter jabatan
   - ORDER BY p.id ASC
2. `getById($id)`: SELECT * FROM pegawai WHERE id = ? LIMIT 1
3. `getByNip($nip)`: SELECT * FROM pegawai WHERE nip = ? LIMIT 1
4. `getByUserId($userId)`: SELECT * FROM pegawai WHERE user_id = ? LIMIT 1
5. `create($data)`: INSERT INTO pegawai (user_id, nip, nama_lengkap, jabatan, status, kuota_cuti)
6. `update($id, $data)`: UPDATE pegawai SET nip=?, nama_lengkap=?, jabatan=?, status=? WHERE id=?
7. `delete($id)`: DELETE FROM pegawai WHERE id=?
8. `countAktif()`: hitung total pegawai dengan status 'aktif'

Berikan isi lengkap file app/Models/Pegawai.php.
```

**Output:** `app/Models/Pegawai.php`

---

#### Step 11 — File: app/Models/Cuti.php

```
Buat file app/Models/Cuti.php — Model Pengajuan Cuti monolitik dengan PDO:

Methods:
1. `getAll($pegawaiId = null)`:
   - SELECT c.*, p.nama_lengkap, p.nip FROM cuti c JOIN pegawai p ON c.pegawai_id = p.id
   - Jika $pegawaiId diisi, filter WHERE c.pegawai_id = ?
   - ORDER BY c.created_at DESC
2. `getById($id)`: ambil single data cuti berdasarkan ID
3. `getKuotaInfo($pegawaiId, $tahun = null)`:
   - Hitung kuota_tahunan (12 hari), total cuti APPROVED tahun berjalan, total cuti PENDING, dan sisa_kuota
4. `create($data)`: INSERT INTO cuti (pegawai_id, tgl_mulai, tgl_selesai, alasan, status) VALUES (?, ?, ?, ?, 'PENDING')
5. `updateStatus($id, $status)`: UPDATE cuti SET status = ? WHERE id = ? (APPROVED atau REJECTED)
6. `countPending()`: hitung jumlah pengajuan cuti yang masih PENDING

Berikan isi lengkap file app/Models/Cuti.php.
```

**Output:** `app/Models/Cuti.php`

---

### FASE E: CONTROLLERS MONOLITIK (Step 12–15)

---

#### Step 12 — File: app/Controllers/AuthController.php

```
Buat file app/Controllers/AuthController.php — Controller Login & Logout Monolitik:

Methods:
1. `showLogin()`:
   - Jika sudah login, redirect('/dashboard')
   - Render view 'auth/login'
2. `login()`:
   - Validasi CSRF token (`csrf_verify()`)
   - Baca $_POST['username'] dan $_POST['password']
   - Jika kosong, flash_set('error', 'Username dan password wajib diisi'), redirect('/login')
   - Cari user via User::findByUsername()
   - Verifikasi status aktif dan password_verify()
   - Jika tidak cocok, flash_set('error', 'Kredensial tidak valid'), redirect('/login')
   - auth_login($user)
   - flash_set('success', 'Selamat datang kembali!')
   - redirect('/dashboard')
3. `logout()`:
   - auth_logout()
   - redirect('/login')

Berikan isi lengkap file app/Controllers/AuthController.php.
```

**Output:** `app/Controllers/AuthController.php`

---

#### Step 13 — File: app/Controllers/DashboardController.php

```
Buat file app/Controllers/DashboardController.php — Controller Dashboard Monolitik:

Methods:
1. `index()`:
   - require_auth()
   - Ambil data user aktif
   - Ambil statistik: total pegawai aktif (`Pegawai::countAktif()`), pengajuan cuti pending (`Cuti::countPending()`)
   - Render view 'dashboard/index' membawa data user dan metrik ringkasan

Berikan isi lengkap file app/Controllers/DashboardController.php.
```

**Output:** `app/Controllers/DashboardController.php`

---

#### Step 14 — File: app/Controllers/PegawaiController.php

```
Buat file app/Controllers/PegawaiController.php — Controller Manajemen Pegawai:

Methods:
1. `index()`:
   - require_auth()
   - Baca query param `search`, `status`, `jabatan`
   - Ambil data pegawai via Pegawai::getAll(...)
   - Render view 'pegawai/index'
2. `create()`:
   - require_role('hr', 'admin')
   - Render view 'pegawai/form' (mode create)
3. `store()`:
   - require_role('hr', 'admin')
   - csrf_verify()
   - Validasi input wajib (nip, nama_lengkap, jabatan, status)
   - Cek keunikan NIP via Pegawai::getByNip() -> jika sudah ada flash error & redirect
   - Simpan via Pegawai::create()
   - flash_set('success', 'Data pegawai berhasil ditambahkan')
   - redirect('/pegawai')
4. `edit()`:
   - require_role('hr', 'admin')
   - Ambil ID dari $_GET['id']
   - Ambil data pegawai -> jika tidak ditemukan 404
   - Render view 'pegawai/form' (mode edit)
5. `update()`:
   - require_role('hr', 'admin')
   - csrf_verify()
   - Validasi input & cek duplikasi NIP selain id yang diedit
   - Pegawai::update()
   - flash_set('success', 'Data pegawai berhasil diperbarui')
   - redirect('/pegawai')
6. `delete()`:
   - require_role('admin')
   - csrf_verify()
   - Pegawai::delete($_POST['id'])
   - flash_set('success', 'Data pegawai berhasil dihapus')
   - redirect('/pegawai')

Berikan isi lengkap file app/Controllers/PegawaiController.php.
```

**Output:** `app/Controllers/PegawaiController.php`

---

#### Step 15 — File: app/Controllers/CutiController.php

```
Buat file app/Controllers/CutiController.php — Controller Pengajuan Cuti Monolitik:

Methods:
1. `index()`:
   - require_auth()
   - Jika role pegawai: cari data pegawai miliknya, ambil cuti miliknya + info kuota cuti tahunan (12 hari & sisa kuota)
   - Jika role manajer / hr / admin: ambil semua data cuti
   - Render view 'cuti/index'
2. `create()`:
   - require_role('pegawai')
   - Ambil info sisa kuota cuti pegawai
   - Render view 'cuti/form'
3. `store()`:
   - require_role('pegawai')
   - csrf_verify()
   - Validasi tanggal selesai >= tanggal mulai, alasan wajib
   - Hitung durasi hari: pastikan durasi cuti <= sisa kuota tahunan
   - Jika melebihi kuota, flash_set('error', 'Durasi cuti melebihi sisa kuota tahunan!'), redirect('/cuti/ajukan')
   - Cuti::create(...)
   - flash_set('success', 'Pengajuan cuti berhasil diajukan')
   - redirect('/cuti')
4. `updateStatus()`:
   - require_role('manajer')
   - csrf_verify()
   - Ambil id cuti dan status (APPROVED / REJECTED)
   - Cuti::updateStatus($id, $status)
   - flash_set('success', 'Status cuti berhasil diperbarui')
   - redirect('/cuti')

Berikan isi lengkap file app/Controllers/CutiController.php.
```

**Output:** `app/Controllers/CutiController.php`

---

### FASE F: ROUTING & ENTRY POINT MONOLITIK (Step 16–17)

---

#### Step 16 — File: includes/Router.php

```
Buat file includes/Router.php — Router Monolitik berbasis URL Path:

Spesifikasi:
- Method `get($uri, $handler)`
- Method `post($uri, $handler)`
- Method `dispatch($requestUri, $requestMethod)`
- Parsing URL path dan query string
- Eksekusi handler berupa callable function atau array `[ClassName, 'methodName']`
- Tangani route 404: tampilkan halaman not found dengan layout header & footer Eduparx

Berikan isi lengkap file includes/Router.php.
```

**Output:** `includes/Router.php`

---

#### Step 17 — File: public/index.php & public/.htaccess

```
Buat 2 file Front Controller monolitik:

FILE 1: public/.htaccess
Spesifikasi:
- RewriteEngine On
- RewriteCond %{REQUEST_FILENAME} !-f
- RewriteCond %{REQUEST_FILENAME} !-d
- RewriteRule ^(.*)$ index.php [QSA,L]

FILE 2: public/index.php
Spesifikasi:
- session_start();
- Inisialisasi error reporting
- Require seluruh dependensi (database, helpers, auth, Router, models, controllers)
- Health check route:
  - GET `/health`: return JSON `{"status": "ok", "app": "PT INIX Monolith"}`
- Web Routes:
  - GET  `/`                 -> redirect('/dashboard')
  - GET  `/login`            -> [AuthController, 'showLogin']
  - POST `/login`            -> [AuthController, 'login']
  - GET  `/logout`           -> [AuthController, 'logout']
  - GET  `/dashboard`        -> [DashboardController, 'index']
  - GET  `/pegawai`          -> [PegawaiController, 'index']
  - GET  `/pegawai/tambah`   -> [PegawaiController, 'create']
  - POST `/pegawai/simpan`   -> [PegawaiController, 'store']
  - GET  `/pegawai/edit`     -> [PegawaiController, 'edit']
  - POST `/pegawai/update`   -> [PegawaiController, 'update']
  - POST `/pegawai/hapus`    -> [PegawaiController, 'delete']
  - GET  `/cuti`             -> [CutiController, 'index']
  - GET  `/cuti/ajukan`      -> [CutiController, 'create']
  - POST `/cuti/simpan`      -> [CutiController, 'store']
  - POST `/cuti/status`      -> [CutiController, 'updateStatus']
- Eksekusi Router `dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD'])`

Berikan isi lengkap kedua file tersebut.
```

**Output:** `public/.htaccess`, `public/index.php`

---

### FASE G: ASSETS & DESIGN SYSTEM EDUPARX (Step 18–19)

---

#### Step 18 — File: public/assets/css/style.css (Pure CSS Eduparx Design System)

```
Buat file public/assets/css/style.css — Styling visual bertema Eduparx Training Catalog murni dalam Pure CSS (tanpa framework eksternal):

Spesifikasi:
1. CSS Variables:
   --eduparx-primary: #204ecf;       /* Biru Utama Eduparx */
   --eduparx-primary-dark: #1b2a4e;  /* Biru Gelap Hero */
   --eduparx-secondary: #038fde;     /* Biru Langit */
   --eduparx-accent: #ff7a00;        /* Oranye Inixindo */
   --eduparx-dark: #262d3d;          /* Teks Judul */
   --eduparx-text: #4b5563;          /* Teks Paragraf */
   --eduparx-bg: #f8fafd;            /* Background Halaman */
   --eduparx-card-bg: #ffffff;
   --eduparx-border: #e8edf5;
   --eduparx-radius: 10px;

2. Typography: font-family 'Inter', -apple-system, sans-serif;
3. Header / Navbar (.eduparx-navbar): Sticky top, putih, logo box, nav items, user badge pill, tombol logout.
4. Hero Banner (.training-page-header): Linear gradient 135deg #162447 ke #204ecf, breadcrumb, highlight pill tags (Rating 4.9, 32 JP, Certified).
5. 2-Column Catalog Layout (.catalog-layout): Sidebar filter kiri (280px) + Konten kanan (1fr). Responsive pada layar mobile (<= 992px).
6. Card Grid Pegawai (.eduparx-card-grid & .course-card): Thumbnail avatar gradient, nama tegas, NIP, status badge (.badge-aktif hijau, .badge-nonaktif abu-abu, .badge-pending oranye), tombol aksi.
7. Data Table (.eduparx-table): Semantic table dengan header rapi, zebra striping, responsive table container.
8. View Toggle Switch: Tombol switch tampilan Card Grid ↔ Data Table.
9. Multi-Column Footer (.eduparx-footer): Background gelap #1a2232, 3 kolom info (Inixindo, Training Catalog, Alamat Kantor).
10. Alert & Modal Dialog: Tampilan pesan sukses (.alert-success), error (.alert-danger), modal backdrop & confirm dialog.

Berikan isi lengkap file public/assets/css/style.css.
```

**Output:** `public/assets/css/style.css`

---

#### Step 19 — File: public/assets/js/app.js (Interaktivitas Frontend Monolitik)

```
Buat file public/assets/js/app.js untuk mendukung interaktivitas client-side pada halaman monolitik:

Fitur:
1. View Toggle (Card Grid ↔ Data Table):
   - Beralih tampilan antara Card Grid dan Data Table pada halaman /pegawai.
   - Simpan status tampilan di localStorage ('eduparx_view_mode').
2. Modal Konfirmasi Hapus:
   - Handler saat tombol hapus diklik: munculkan dialog modal konfirmasi (role="alertdialog").
   - Isi form hidden input dengan ID target yang akan dihapus.
   - Tombol konfirmasi memiliki `data-testid="btn-confirm-delete"` dan batal `data-testid="btn-cancel-delete"`.
3. Hitung Durasi Cuti Otomatis:
   - Pada halaman /cuti/ajukan, saat tgl_mulai dan tgl_selesai diisi, otomatis hitung durasi hari cuti dan render ke `<span data-testid="durasi-cuti">`.

Berikan isi lengkap file public/assets/js/app.js.
```

**Output:** `public/assets/js/app.js`

---

### FASE H: TEMPLATE VIEWS EDUPARX (Step 20–28)

---

#### Step 20 — File: app/Views/layouts/header.php

```
Buat file app/Views/layouts/header.php — Layout template bagian atas monolitik:

Spesifikasi:
- DOCTYPE html, lang="id"
- Google Font Inter: <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
- Link stylesheet: `/assets/css/style.css`
- Title dinamis: `$pageTitle ?? 'PT INIX | Eduparx — Sistem Kepegawaian & Training IT'`
- Top Navbar Eduparx:
  - Branding Logo: "INIXINDO | Eduparx"
  - Nav links: Home (`/dashboard`), Data Pegawai (`/pegawai`), Pengajuan Cuti (`/cuti`)
  - Status User login: Nama user, Role badge (`badge-role`), Tombol Logout (`/logout`)
- Flash alert container: render alert sukses (`data-testid="success-message"`) atau alert error (`data-testid="form-error"` / `data-testid="error-message"`) jika ada pesan flash di session.

Berikan isi lengkap file app/Views/layouts/header.php.
```

**Output:** `app/Views/layouts/header.php`

---

#### Step 21 — File: app/Views/layouts/footer.php

```
Buat file app/Views/layouts/footer.php — Layout template bagian bawah monolitik:

Spesifikasi:
- Eduparx Multi-Column Footer:
  - Kolom 1: Profil PT Inixindo & Eduparx — Solusi Pelatihan & Akselerasi Digital.
  - Kolom 2: Program Unggulan: AI Engineer, Software QA Testing, Cloud Computing.
  - Kolom 3: Kantor Resmi: Jl. Kenari No. 69 Yogyakarta, Hotline, Email: support@eduparx.id.
  - Bottom bar: `© 2026 PT Inixindo (Eduparx). Seluruh Hak Cipta Dilindungi.`
- Script tag ke `/assets/js/app.js`

Berikan isi lengkap file app/Views/layouts/footer.php.
```

**Output:** `app/Views/layouts/footer.php`

---

#### Step 22 — File: app/Views/layouts/confirm_dialog.php

```
Buat file app/Views/layouts/confirm_dialog.php — Modal Dialog Konfirmasi Hapus Data:

Spesifikasi:
- Modal semantic HTML dengan `role="alertdialog"`
- Form POST action="/pegawai/hapus"
- CSRF field (`csrf_field()`)
- Hidden input id: `<input type="hidden" name="id" id="delete-target-id">`
- Tombol:
  - Tombol Konfirmasi Hapus: `data-testid="btn-confirm-delete"`
  - Tombol Batal: `data-testid="btn-cancel-delete"`

Berikan isi lengkap file app/Views/layouts/confirm_dialog.php.
```

**Output:** `app/Views/layouts/confirm_dialog.php`

---

#### Step 23 — File: app/Views/auth/login.php

```
Buat file app/Views/auth/login.php — Halaman Login Monolitik:

Spesifikasi:
- Card login container modern berlogo Inixindo & Eduparx
- Form login method="POST" action="/login"
- Sertakan CSRF field (`csrf_field()`)
- Elemen form dengan `data-testid` wajib:
  - Input username: `data-testid="input-username"` (name="username", id="username", label "Email / Username")
  - Input password: `data-testid="input-password"` (name="password", id="password", type="password", label "Password")
  - Tombol Submit: `data-testid="btn-login"` (text "Masuk ke Portal")
  - Error alert jika ada: `data-testid="error-message"` (role="alert")
- Kotak informasi demo credential untuk memudahkan pengujian:
  - admin@inix.com / Admin@123!
  - hr@inix.com / Hr@123!
  - manager@inix.com / Manager@123!
  - pegawai@inix.com / Pegawai@123!

Berikan isi lengkap file app/Views/auth/login.php.
```

**Output:** `app/Views/auth/login.php`

---

#### Step 24 — File: app/Views/dashboard/index.php

```
Buat file app/Views/dashboard/index.php — Halaman Dashboard Monolitik:

Spesifikasi:
- Include header.php dan footer.php
- Training Hero Section:
  - Welcome greeting: `<h1 data-testid="welcome-msg">Selamat datang, <?= e($user['username']) ?>!</h1>`
  - Subtitle: "Portal Kepegawaian & Pelatihan IT PT Inixindo"
  - Highlight pill tags Eduparx
- 3 Metric Summary Cards:
  1. Total Pegawai Aktif (`<?= $totalPegawai ?>`)
  2. Pengajuan Cuti Menunggu Review (`<?= $totalCutiPending ?>`)
  3. Program Pelatihan AI Engineer & QA Berjalan
- Quick Shortcut Links ke `/pegawai` dan `/cuti`
- Role badge indicator yang menampilkan role aktif pengguna saat ini.

Berikan isi lengkap file app/Views/dashboard/index.php.
```

**Output:** `app/Views/dashboard/index.php`

---

#### Step 25 — File: app/Views/pegawai/index.php (Katalog Pegawai 2-Kolom)

```
Buat file app/Views/pegawai/index.php — Halaman Katalog Pegawai 2-Kolom Eduparx:

Spesifikasi:
1. Hero Section Eduparx: Breadcrumb `Home > Katalog Pegawai & Talenta AI`
2. Layout 2-Kolom:
   - KIRI (Sidebar Filter):
     - Form pencarian method="GET" action="/pegawai"
     - Search input: `data-testid="search-pegawai"` (name="search")
     - Filter Kategori/Jabatan: AI Engineer, QA Automation, Staff HR, Manajer
     - Filter Status: Aktif, Nonaktif
     - Tombol Submit Filter & Tombol Reset
   - KANAN (Konten Utama):
     - Top bar: Counter jumlah data + Tombol "Tambah Pegawai" (`data-testid="btn-tambah"`, tampil untuk HR/Admin) + Tombol View Toggle (Card Grid ↔ Table)
     - Tampilan Card Grid (Eduparx Course Card Style):
       - Card per pegawai: avatar ilustrasi, badge kategori, NIP, status badge, tombol edit (`data-testid="btn-edit-<?= $p['id'] ?>"`) & tombol hapus (`data-testid="btn-delete-<?= $p['id'] ?>"`)
     - Tampilan Data Table:
       - Semantic table: `data-testid="pegawai-table"`
       - Kolom: NIP, Nama Lengkap, Jabatan, Status, Aksi
3. Include layouts/confirm_dialog.php untuk konfirmasi delete.

Berikan isi lengkap file app/Views/pegawai/index.php.
```

**Output:** `app/Views/pegawai/index.php`

---

#### Step 26 — File: app/Views/pegawai/form.php (Form Tambah / Edit)

```
Buat file app/Views/pegawai/form.php — Form Input & Update Data Pegawai:

Spesifikasi:
- Card container berdesain Eduparx
- Title dinamis: "Tambah Pegawai Baru" atau "Edit Data Pegawai"
- Form method="POST" action="<?= $isEdit ? '/pegawai/update' : '/pegawai/simpan' ?>"
- CSRF field (`csrf_field()`)
- Hidden input id (jika mode edit)
- Form fields lengkap dengan label terhubung dan `data-testid`:
  - Input NIP: `data-testid="input-nip"` (name="nip", id="nip", required)
  - Input Nama Lengkap: `data-testid="input-nama"` (name="nama_lengkap", id="nama_lengkap", required)
  - Input Jabatan: `data-testid="input-jabatan"` (name="jabatan", id="jabatan", required)
  - Select Status: `data-testid="select-status"` (name="status", options: 'aktif', 'nonaktif')
  - Button Submit: `data-testid="btn-simpan"` (text "Simpan Data")
  - Button Batal: `data-testid="btn-batal"` (link kembali ke /pegawai)
  - Alert error: `data-testid="form-error"`

Berikan isi lengkap file app/Views/pegawai/form.php.
```

**Output:** `app/Views/pegawai/form.php`

---

#### Step 27 — File: app/Views/cuti/index.php (List Pengajuan Cuti)

```
Buat file app/Views/cuti/index.php — Halaman Manajemen Pengajuan Cuti Monolitik:

Spesifikasi:
- Include header.php dan footer.php
- Hero Banner: "Pengajuan Cuti & Izin Kerja Pegawai PT INIX"
- Card Ringkasan Kuota (khusus role Pegawai):
  - Kuota Tahunan: `data-testid="kuota-tahunan"` (12 Hari)
  - Sisa Kuota Cuti: `data-testid="sisa-kuota-cuti"`
- Button "Ajukan Cuti Baru": `data-testid="btn-ajukan-cuti"` (link ke `/cuti/ajukan`, tampil untuk role Pegawai)
- Tabel Cuti: `data-testid="cuti-table"`
  - Kolom: No, Nama Pegawai, Tanggal Mulai, Tanggal Selesai, Alasan, Status, Aksi
  - Status badge: .badge-pending (kuning/oranye), .badge-approved (hijau), .badge-rejected (merah)
  - Aksi untuk role Manajer pada status PENDING:
    - Form POST `/cuti/status` dengan CSRF token
    - Button approve: `data-testid="btn-approve-<?= $c['id'] ?>"`
    - Button reject: `data-testid="btn-reject-<?= $c['id'] ?>"`

Berikan isi lengkap file app/Views/cuti/index.php.
```

**Output:** `app/Views/cuti/index.php`

---

#### Step 28 — File: app/Views/cuti/form.php (Form Pengajuan Cuti)

```
Buat file app/Views/cuti/form.php — Form Pengajuan Cuti Baru:

Spesifikasi:
- Card container bertema Eduparx
- Info Kuota: menampilkan sisa kuota cuti tahunan aktif (`data-testid="sisa-kuota-cuti"`)
- Form method="POST" action="/cuti/simpan"
- CSRF field (`csrf_field()`)
- Elemen form lengkap dengan label & `data-testid`:
  - Input Tanggal Mulai: `data-testid="input-tgl-mulai"` (type="date", name="tgl_mulai", required)
  - Input Tanggal Selesai: `data-testid="input-tgl-selesai"` (type="date", name="tgl_selesai", required)
  - Estimasi Durasi Hari Cuti: `<span data-testid="durasi-cuti">0 Hari</span>` (dihitung realtime via JS)
  - Textarea Alasan: `data-testid="input-alasan"` (name="alasan", rows="3", required)
  - Tombol Submit: `data-testid="btn-submit-cuti"` (text "Kirim Pengajuan Cuti")
  - Alert error validasi: `data-testid="validation-error"` (role="alert")
- Validasi client-side: tgl_selesai >= tgl_mulai, alasan minimal 3 karakter, durasi <= sisa kuota.

Berikan isi lengkap file app/Views/cuti/form.php.
```

**Output:** `app/Views/cuti/form.php`

---

### FASE I: DOCKER MONOLITIK & PANDUAN PENGUJIAN (Step 29–30)

---

#### Step 29 — File: Dockerfile & docker-compose.yml

```
Buat 2 file deployment container aplikasi monolitik:

FILE 1: Dockerfile
- Base image: `php:8.2-apache`
- Aktifkan Apache rewrite module: `RUN a2enmod rewrite`
- Install ekstensi PDO MySQL: `RUN docker-php-ext-install pdo pdo_mysql`
- Konfigurasi Apache DocumentRoot ke `/var/www/html/public`
- Konfigurasi `AllowOverride All` agar file .htaccess aktif
- Set WORKDIR `/var/www/html`
- Copy source code ke container
- Set permission chown www-data:www-data
- EXPOSE 80
- CMD ["apache2-foreground"]

FILE 2: docker-compose.yml
- version: "3.8"
- 2 Services:
  1. `db`:
     - image: mysql:8.0
     - container_name: inix_db_container
     - environment:
         MYSQL_ROOT_PASSWORD: rootpassword
         MYSQL_DATABASE: inix_db
     - ports:
         - "3306:3306"
     - volumes:
         - mysql_data:/var/lib/mysql
     - healthcheck:
         test: ["CMD", "mysqladmin", "ping", "-h", "localhost", "-u", "root", "-prootpassword"]
         interval: 5s
         timeout: 5s
         retries: 10
  2. `app`:
     - build: .
     - container_name: inix_monolith_container
     - depends_on:
         db:
           condition: service_healthy
     - environment:
         DB_HOST: db
         DB_PORT: 3306
         DB_NAME: inix_db
         DB_USER: root
         DB_PASSWORD: rootpassword
     - ports:
         - "8000:80"
     - volumes:
         - .:/var/www/html
- Volumes:
  mysql_data:

Berikan isi lengkap kedua file tersebut.
```

**Output:** `Dockerfile`, `docker-compose.yml`

---

#### Step 30 — File: README.md (Quick Start Monolitik & Panduan Pengujian)

```
Buat file README.md di root project inix-kepegawaian/ yang memuat dokumentasi lengkap:

Spesifikasi:
1. Deskripsi Project: Aplikasi Monolitik Kepegawaian & Pelatihan PT INIX (Eduparx Theme) berbasis PHP Native & MySQL.
2. Quick Start One-Command:
   ```bash
   docker-compose up -d
   docker exec -it inix_monolith_container php database/seed.php
   ```
3. URL Akses:
   - Aplikasi Monolitik (Eduparx UI): http://localhost:8000
   - Health Check: http://localhost:8000/health
   - Database MySQL: localhost:3306 (user: root, pass: rootpassword, db: inix_db)
4. Demo Akun Pengujian:
   - Admin: admin@inix.com / Admin@123!
   - HR: hr@inix.com / Hr@123!
   - Manager: manager@inix.com / Manager@123!
   - Pegawai: pegawai@inix.com / Pegawai@123!
5. Panduan Pengujian untuk 9 Tools Testing:
   - Chrome DevTools: Inspect session cookie, form request, semantic HTML
   - Postman: Testing POST login dan form action menggunakan Cookie Jar
   - Playwright: Query seluruh `data-testid`, interaksi input, button, dan modal delete
   - k6: Uji performa rendering monolitik & login concurrency
   - OWASP ZAP: Crawl form monolitik & deteksi header default
   - DBeaver: Koneksi ke MySQL port 3306 untuk inspeksi database langsung

Berikan isi lengkap file README.md.
```

**Output:** `README.md`

---

**[E] EXPECTATION:**

- **Output:** Source code lengkap untuk setiap file yang diminta pada masing-masing step.
- **Visual Design:** Menyerupai estetika modern **Eduparx Training Catalog** (skema biru `#204ecf`, hero gradient `#1b2a4e` ke `#204ecf`, highlight pill badges, 2-kolom layout dengan sidebar filter, card grid + view toggle table, multi-column footer).
- **Semua Test Locator Utuh:** Seluruh `data-testid`, semantic attributes, dan accessible names wajib ada sesuai tabel spesifikasi agar tidak mematahkan Playwright script.
- **Arsitektur Monolitik Murni:** Satu codebase PHP Native utuh (Server-Side Rendered MVC), tanpa frontend terpisah, tanpa JWT/Bearer token, murni session-based authentication dengan CSRF guard.

---

**[N] NARROWING / CONSTRAINTS:**

- JANGAN jalankan perintah terminal menggunakan PowerShell, gunakan perintah terminal dengan CMD.
- **GUNAKAN ARSITEKTUR MONOLITIK PHP NATIVE (PDO MySQL)** — **JANGAN** menggunakan framework berat (Laravel / CodeIgniter / Symfony) atau Express.js / Node.js / React / Vue.
- **JANGAN** menggunakan Tailwind CSS atau library CSS berat lainnya — gunakan **Pure CSS** (`style.css`) agar kode ringkas, performan, dan mudah di-inspect DevTools.
- **JANGAN** memisahkan backend dan frontend menjadi dua service terpisah (harus satu kesatuan monolitik Server-Side Rendering).
- **JANGAN** menghilangkan atribut `data-testid` pada element apa pun.
- **JANGAN** implementasikan security headers secara ketat (biarkan sebagian terbuka agar OWASP ZAP menemukan vulnerability latihan).

---

## Verifikasi Checklist Testability & Desain Eduparx

- [ ] **Desain Eduparx:** Navbar, Hero Gradient, Filter Sidebar, Card Grid, dan Footer menyerupai `eduparx.id/home/training?catalog=ai-engineer`
- [ ] **View Toggle:** Halaman Pegawai memiliki opsi Card Grid view dan Data Table view
- [ ] **Chrome DevTools:** Network tab menangkap SSR HTML & session cookie, Console bersih tanpa unhandled exceptions
- [ ] **Playwright Locators:** Semua `data-testid` terpasang dan dapat di-query dengan `page.getByTestId(...)`
- [ ] **Lighthouse:** Score Accessibility ≥ 75 berkat semantic tags (`<nav>`, `<header>`, `<main>`, `<aside>`, `<footer>`, `<table>`)
- [ ] **k6 Performance:** Endpoint monolitik dapat diuji beban tanpa rate limiter
- [ ] **OWASP ZAP:** Spidering mengenali form HTML monolitik dan mendeteksi intentional missing headers
- [ ] **Docker:** `docker-compose up -d` langsung menjalankan aplikasi monolitik tanpa langkah manual tambahan
