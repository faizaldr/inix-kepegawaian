# MODULE INVENTORY
## Sistem Informasi Kepegawaian & Pelatihan PT INIX

Dokumen ini mendokumentasikan inventarisasi seluruh modul fungsional, submodul, file pengendali, dan hak akses aktor yang teridentifikasi dalam basis kode aplikasi.

---

### 1. Inventaris Modul Sistem

| ID Modul | Nama Modul | Submodul | File Controller Terkait | File Model Terkait | File View Terkait | Hak Akses (Aktor) |
|---|---|---|---|---|---|---|
| **MOD-01** | Core Infrastructure & Monitoring | Health Check API | `public/index.php` (Closure) | N/A | JSON Direct Output | Public / All |
| **MOD-02** | Autentikasi & Sesi | Tampilan Form Login | `app/Controllers/AuthController.php::showLogin` | `app/Models/User.php` | `app/Views/auth/login.php` | Public |
| **MOD-02** | Autentikasi & Sesi | Pemrosesan Login | `app/Controllers/AuthController.php::login` | `app/Models/User.php` | N/A (Redirect) | Public |
| **MOD-02** | Autentikasi & Sesi | Logout Sesi | `app/Controllers/AuthController.php::logout` | N/A | N/A (Redirect) | Authenticated |
| **MOD-03** | Dashboard & Metrik | Visualisasi Ringkasan & Metrik | `app/Controllers/DashboardController.php::index` | `Pegawai.php`, `Cuti.php` | `app/Views/dashboard/index.php` | Authenticated (Semua Role) |
| **MOD-04** | Master Data Pegawai | Katalog & Tabel Pegawai | `app/Controllers/PegawaiController.php::index` | `app/Models/Pegawai.php` | `app/Views/pegawai/index.php` | Authenticated (Semua Role) |
| **MOD-04** | Master Data Pegawai | Formulir Tambah Pegawai | `app/Controllers/PegawaiController.php::create` | `app/Models/Pegawai.php` | `app/Views/pegawai/form.php` | Admin, HR |
| **MOD-04** | Master Data Pegawai | Simpan Pegawai Baru | `app/Controllers/PegawaiController.php::store` | `app/Models/Pegawai.php` | N/A (Redirect) | Admin, HR |
| **MOD-04** | Master Data Pegawai | Formulir Edit Pegawai | `app/Controllers/PegawaiController.php::edit` | `app/Models/Pegawai.php` | `app/Views/pegawai/form.php` | Admin, HR |
| **MOD-04** | Master Data Pegawai | Pembaruan Pegawai | `app/Controllers/PegawaiController.php::update` | `app/Models/Pegawai.php` | N/A (Redirect) | Admin, HR |
| **MOD-04** | Master Data Pegawai | Penghapusan Data Pegawai | `app/Controllers/PegawaiController.php::delete` | `app/Models/Pegawai.php` | N/A (Redirect) | Admin |
| **MOD-05** | Manajemen Cuti | Daftar & Riwayat Pengajuan | `app/Controllers/CutiController.php::index` | `app/Models/Cuti.php`, `Pegawai.php` | `app/Views/cuti/index.php` | Authenticated (Semua Role) |
| **MOD-05** | Manajemen Cuti | Formulir Pengajuan Cuti | `app/Controllers/CutiController.php::create` | `app/Models/Cuti.php`, `Pegawai.php` | `app/Views/cuti/form.php` | Pegawai |
| **MOD-05** | Manajemen Cuti | Simpan Permohonan Cuti | `app/Controllers/CutiController.php::store` | `app/Models/Cuti.php`, `Pegawai.php` | N/A (Redirect) | Pegawai |
| **MOD-05** | Manajemen Cuti | Approval / Rejection Cuti | `app/Controllers/CutiController.php::updateStatus` | `app/Models/Cuti.php` | N/A (Redirect) | Manajer |

---

### 2. Rincian Fitur per Modul

#### MOD-01: Core Infrastructure & Monitoring
- **Health Check Endpoint (`GET /health`):** Mengembalikan status operasional sistem berupa respon JSON berisi parameter status, identitas aplikasi, dan stempel waktu ISO.

#### MOD-02: Autentikasi & Pengelolaan Sesi
- **Form Login (`GET /login`):** Menampilkan antarmuka login bertema Eduparx dengan pesan flash alert. Jika sesi sudah aktif, sistem secara otomatis mengarahkan ke `/dashboard`.
- **Proses Autentikasi (`POST /login`):** Memverifikasi integritas CSRF, mengecek field wajib, melakukan pencocokan hash password bcrypt via `password_verify()`, memeriksa status keaktifan user (`is_active`), memperbarui ID sesi via `session_regenerate_id()`, dan melakukan redirect ke intended URL.
- **Terminasi Sesi (`GET/POST /logout`):** Mengosongkan data sesi, menghapus cookie sesi klien dengan menetapkan expired time mundur, dan menghancurkan sesi di server.

#### MOD-03: Dashboard & Metrik
- **Overview Operasional (`GET /dashboard`):** Menampilkan banner hero personalia Eduparx, kartu ucapan selamat datang, metrik jumlah pegawai aktif (`Pegawai::countAktif()`), dan jumlah permohonan cuti yang berstatus PENDING (`Cuti::countPending()`).

#### MOD-04: Master Data Pegawai
- **Katalog & Filter Pegawai (`GET /pegawai`):** Menampilkan daftar pegawai dengan dukungan pencarian teks (pencocokan nama lengkap dan NIP), penyaringan status (`aktif`/`nonaktif`), penyaringan jabatan, serta toggle tampilan (Card Grid vs Data Table).
- **Entri Pegawai Baru (`GET /pegawai/tambah` & `POST /pegawai/simpan`):** Validasi field wajib (NIP, Nama Lengkap, Jabatan), verifikasi keunikan NIP secara database, dan inisialisasi default kuota cuti tahunan sebesar 12 hari.
- **Pembaruan Pegawai (`GET /pegawai/edit` & `POST /pegawai/update`):** Menampilkan formulir prapopulasi data pegawai dan memverifikasi agar perubahan NIP tidak bentrok dengan pegawai lain.
- **Penghapusan Pegawai (`POST /pegawai/hapus`):** Menghapus entitas pegawai secara permanen; hanya diizinkan untuk pengguna berhak akses `admin`.

#### MOD-05: Pengajuan & Manajemen Cuti
- **Monitoring Cuti (`GET /cuti`):** Untuk pengguna role `pegawai`, halaman hanya menampilkan riwayat cuti miliknya beserta kartu ringkasan kuota tahunan, total terpakai, jumlah pending, dan sisa kuota. Untuk role manajemen (`manajer`, `hr`, `admin`), halaman menampilkan rekapitulasi seluruh pengajuan dari semua pegawai.
- **Pengajuan Cuti Baru (`GET /cuti/ajukan` & `POST /cuti/simpan`):** Memastikan pegawai memiliki relasi data personil, memvalidasi rentang tanggal (`tgl_selesai >= tgl_mulai`), menghitung durasi hari kerja kalender, dan memastikan durasi tidak melebihi sisa kuota cuti tahunan berjalan.
- **Persetujuan Permohonan (`POST /cuti/status`):** Khusus role `manajer`, memungkinkan pengubahan status pengajuan cuti berstatus `PENDING` menjadi `APPROVED` atau `REJECTED`.
