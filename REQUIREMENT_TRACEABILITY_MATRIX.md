# REQUIREMENT TRACEABILITY MATRIX (RTM)
## Sistem Informasi Kepegawaian & Pelatihan PT INIX

Matriks keterlacakan ini menghubungkan setiap Kebutuhan Fungsional (FR) dan Kebutuhan Non-Fungsional (NFR) dengan user story, rute web, controller, model, view, skema basis data, dan script pengujian otomatis Playwright.

---

### 1. Matriks Kebutuhan Fungsional (Functional Requirements)

| Req ID | Deskripsi Kebutuhan | Aktor Utama | Rute Endpoint | Controller & Handler | Model Data Terkait | Tabel Database | View / UI Template | Artefak Uji (Playwright / Test) | Status Verifikasi |
|---|---|---|---|---|---|---|---|---|---|
| **FR-001** | Pengguna dapat melakukan login menggunakan username dan password valid untuk membuat sesi kerja aktif. | Public / All Users | `GET /login`<br>`POST /login` | `AuthController::showLogin`<br>`AuthController::login` | `User::findByUsername` | `users` | `app/Views/auth/login.php` | `test/login_positive_test.spec.ts` | **Verified** |
| **FR-002** | Sistem menampilkan notifikasi kesalahan jika username/password kosong, salah, atau akun dinonaktifkan (`is_active = 0`). | Public / All Users | `POST /login` | `AuthController::login` | `User::findByUsername` | `users` | `app/Views/auth/login.php` | `test/login_negative_test.spec.ts` | **Verified** |
| **FR-003** | Pengguna dapat mengakhiri sesi kerja (logout) secara aman dan diarahkan kembali ke halaman login. | Authenticated | `GET /logout`<br>`POST /logout` | `AuthController::logout` | N/A (`auth_logout()`) | Sesi PHP (`PHPSESSID`) | `app/Views/layouts/header.php` | `data-testid="btn-logout"` | **Verified** |
| **FR-004** | Pengguna terautentikasi dapat melihat dashboard ringkasan statistik (pegawai aktif dan permohonan cuti pending). | Authenticated | `GET /dashboard` | `DashboardController::index` | `Pegawai::countAktif`<br>`Cuti::countPending` | `pegawai`, `cuti` | `app/Views/dashboard/index.php` | `data-testid="welcome-msg"` | **Verified** |
| **FR-005** | Pengguna dapat melihat katalog daftar pegawai dengan fitur pencarian teks serta filter status dan jabatan. | Authenticated | `GET /pegawai` | `PegawaiController::index` | `Pegawai::getAll` | `pegawai`, `users` | `app/Views/pegawai/index.php` | `data-testid="pegawai-table"` | **Verified** |
| **FR-006** | HR dan Admin dapat menambahkan entitas pegawai baru dengan validasi keunikan NIP dan field mandatori. | Admin, HR | `GET /pegawai/tambah`<br>`POST /pegawai/simpan` | `PegawaiController::create`<br>`PegawaiController::store` | `Pegawai::create`<br>`Pegawai::getByNip` | `pegawai` | `app/Views/pegawai/form.php` | `data-testid="btn-tambah"`, `btn-simpan` | **Verified** |
| **FR-007** | HR dan Admin dapat memperbarui informasi master data pegawai yang sudah ada. | Admin, HR | `GET /pegawai/edit`<br>`POST /pegawai/update` | `PegawaiController::edit`<br>`PegawaiController::update` | `Pegawai::getById`<br>`Pegawai::update` | `pegawai` | `app/Views/pegawai/form.php` | `data-testid="btn-edit-{id}"` | **Verified** |
| **FR-008** | Khusus Admin berwenang menghapus data pegawai dari sistem dengan proteksi token CSRF. | Admin | `POST /pegawai/hapus` | `PegawaiController::delete` | `Pegawai::delete` | `pegawai`, `cuti` (`CASCADE`) | `app/Views/pegawai/index.php` | `data-testid="btn-delete-{id}"` | **Verified** |
| **FR-009** | Pengguna dapat melihat daftar cuti; Pegawai hanya melihat data miliknya beserta sisa kuota, sedangkan Manajer/HR/Admin melihat seluruh pengajuan. | Authenticated | `GET /cuti` | `CutiController::index` | `Cuti::getAll`<br>`Cuti::getKuotaInfo` | `cuti`, `pegawai` | `app/Views/cuti/index.php` | `data-testid="cuti-table"`, `sisa-kuota-cuti` | **Verified** |
| **FR-010** | Pegawai dapat mengajukan permohonan cuti baru dengan validasi rentang tanggal dan sisa kuota tahunan. | Pegawai | `GET /cuti/ajukan`<br>`POST /cuti/simpan` | `CutiController::create`<br>`CutiController::store` | `Cuti::create`<br>`Cuti::getKuotaInfo` | `cuti`, `pegawai` | `app/Views/cuti/form.php` | `data-testid="btn-ajukan-cuti"`, `btn-submit-cuti` | **Verified** |
| **FR-011** | Manajer dapat menyetujui (`APPROVED`) atau menolak (`REJECTED`) permohonan cuti yang berstatus `PENDING`. | Manajer | `POST /cuti/status` | `CutiController::updateStatus` | `Cuti::updateStatus` | `cuti` | `app/Views/cuti/index.php` | `data-testid="btn-approve-{id}"`, `btn-reject-{id}` | **Verified** |
| **FR-012** | Sistem menyediakan probe health check JSON untuk keperluan CI/CD monitoring dan orkestrasi container. | Public / DevOps | `GET /health` | `public/index.php` (line 31-39) | N/A | N/A | JSON Direct Output | curl / Docker healthcheck | **Verified** |

---

### 2. Matriks Kebutuhan Non-Fungsional (Non-Functional Requirements)

| Req ID | Kategori NFR | Spesifikasi Kebutuhan | Artefak Implementasi Kode | Standar / Metrik Pengujian | Status Verifikasi |
|---|---|---|---|---|---|
| **NFR-001** | Security (XSS) | Seluruh output data dinamis disanitasi menggunakan `htmlspecialchars` encoding UTF-8. | `includes/helpers.php` (fungsi `e()`) | Zero XSS injection vulnerabilities. | **Verified** |
| **NFR-002** | Security (SQLi) | Interaksi basis data wajib memakai PDO Prepared Statements dengan parameter binding. | `app/Models/*`, `config/database.php` (`PDO::ATTR_EMULATE_PREPARES => false`) | Zero SQL Injection vulnerabilities. | **Verified** |
| **NFR-003** | Security (CSRF) | Setiap mutasi formulir POST wajib menyertakan dan memverifikasi token CSRF sesi. | `includes/helpers.php` (`csrf_verify()`) | HTTP 403 Forbidden jika CSRF token hilang atau invalid. | **Verified** |
| **NFR-004** | Security (Auth & Hash) | Password disimpan menggunakan hash Bcrypt yang aman; session ID diregenerasi saat login berhasil. | `app/Controllers/AuthController.php`, `includes/auth.php` (`session_regenerate_id(true)`) | Tidak ada plaintext password di basis data. | **Verified** |
| **NFR-005** | Maintainability & Architecture | Arsitektur Front Controller MVC monolitik tanpa dependensi framework eksternal berat. | `public/index.php`, `includes/Router.php`, `app/*` | Struktur kode modular dan mudah dibaca. | **Verified** |
| **NFR-006** | Testability (Automation Ready) | Seluruh elemen penting antarmuka memiliki atribut penanda `data-testid` yang unik. | `app/Views/**/*` | 100% selector selector kompatibel dengan Playwright E2E. | **Verified** |
| **NFR-007** | Performance (SSR Latency) | Waktu respon halaman server-side rendering ≤ 500 ms P95 pada beban operasional standar. | PHP Native 8.2 & PDO MySQL Indexed Queries | Uji beban k6 / ApacheBench. | **Verified** |
