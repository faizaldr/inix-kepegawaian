# API & ROUTE INVENTORY
## Sistem Informasi Kepegawaian & Pelatihan PT INIX

Dokumen ini memetakan seluruh rute HTTP, metode permintaan, pengendali (handler), format payload, proteksi keamanan, dan respon sistem berdasarkan implementasi aktual pada `public/index.php` dan `includes/Router.php`.

---

### 1. Daftar Lengkap Endpoint & Web Routes

| No | Method | URI / Route Path | Tipe Interface | Controller & Handler Method | Parameter / Input Payload | Hak Akses (RBAC) | CSRF Protected | Output / Respon |
|---|---|---|---|---|---|---|---|---|
| 1 | `GET` | `/health` | REST JSON API | `index.php` (Closure line 31-39) | Tidak ada | Public | Tidak | JSON `{"status":"ok","app":"PT INIX Monolith","time":"..."}` (HTTP 200) |
| 2 | `GET` | `/` | Web Route | `index.php` (Closure line 42-44) | Tidak ada | Public | Tidak | 302 Redirect ke `/dashboard` |
| 3 | `GET` | `/login` | Web Route | `AuthController::showLogin` | Tidak ada | Public | Tidak | Render HTML `app/Views/auth/login.php` (HTTP 200) atau 302 Redirect jika authenticated |
| 4 | `POST` | `/login` | Web Route | `AuthController::login` | Form Data: `username`, `password`, `_csrf` | Public | Ya (`csrf_verify()`) | 302 Redirect ke intended URL / `/dashboard` atau `/login` (jika gagal) |
| 5 | `GET` | `/logout` | Web Route | `AuthController::logout` | Tidak ada | Authenticated | Tidak | 302 Redirect ke `/login` |
| 6 | `POST` | `/logout` | Web Route | `AuthController::logout` | Form Data: opsional | Authenticated | Tidak | 302 Redirect ke `/login` |
| 7 | `GET` | `/dashboard` | Web Route | `DashboardController::index` | Tidak ada | Authenticated (All Roles) | Tidak | Render HTML `app/Views/dashboard/index.php` (HTTP 200) |
| 8 | `GET` | `/pegawai` | Web Route | `PegawaiController::index` | Query String: `search`, `status`, `jabatan` | Authenticated (All Roles) | Tidak | Render HTML `app/Views/pegawai/index.php` (HTTP 200) |
| 9 | `GET` | `/pegawai/tambah` | Web Route | `PegawaiController::create` | Tidak ada | `admin`, `hr` | Tidak | Render HTML `app/Views/pegawai/form.php` (HTTP 200) |
| 10 | `POST` | `/pegawai/simpan` | Web Route | `PegawaiController::store` | Form Data: `nip`, `nama_lengkap`, `jabatan`, `status`, `_csrf` | `admin`, `hr` | Ya (`csrf_verify()`) | 302 Redirect ke `/pegawai` (sukses) atau `/pegawai/tambah` (validasi error) |
| 11 | `GET` | `/pegawai/edit` | Web Route | `PegawaiController::edit` | Query String: `id` (integer) | `admin`, `hr` | Tidak | Render HTML `app/Views/pegawai/form.php` (HTTP 200) atau 302 Redirect jika tidak ditemukan |
| 12 | `POST` | `/pegawai/update` | Web Route | `PegawaiController::update` | Form Data: `id`, `nip`, `nama_lengkap`, `jabatan`, `status`, `_csrf` | `admin`, `hr` | Ya (`csrf_verify()`) | 302 Redirect ke `/pegawai` (sukses) atau `/pegawai/edit?id={id}` (error) |
| 13 | `POST` | `/pegawai/hapus` | Web Route | `PegawaiController::delete` | Form Data: `id`, `_csrf` | `admin` | Ya (`csrf_verify()`) | 302 Redirect ke `/pegawai` |
| 14 | `GET` | `/cuti` | Web Route | `CutiController::index` | Tidak ada | Authenticated (All Roles) | Tidak | Render HTML `app/Views/cuti/index.php` (HTTP 200) |
| 15 | `GET` | `/cuti/ajukan` | Web Route | `CutiController::create` | Tidak ada | `pegawai` | Tidak | Render HTML `app/Views/cuti/form.php` (HTTP 200) |
| 16 | `POST` | `/cuti/simpan` | Web Route | `CutiController::store` | Form Data: `tgl_mulai`, `tgl_selesai`, `alasan`, `_csrf` | `pegawai` | Ya (`csrf_verify()`) | 302 Redirect ke `/cuti` (sukses) atau `/cuti/ajukan` (error) |
| 17 | `POST` | `/cuti/status` | Web Route | `CutiController::updateStatus` | Form Data: `id`, `status` (`APPROVED`/`REJECTED`), `_csrf` | `manajer` | Ya (`csrf_verify()`) | 302 Redirect ke `/cuti` |

---

### 2. Spesifikasi Detail Payload Endpoint Terpilih

#### 2.1 Health Check API (`GET /health`)
- **Tujuan:** Probe otomatis container healthcheck & CI/CD deployment pipeline.
- **Request Headers:** None
- **Response Headers:** `Content-Type: application/json`
- **Response Payload (HTTP 200):**
```json
{
  "status": "ok",
  "app": "PT INIX Monolith",
  "time": "2026-11-18T11:23:48+07:00"
}
```

#### 2.2 Autentikasi (`POST /login`)
- **Content-Type:** `application/x-www-form-urlencoded`
- **Body Parameter:**
  - `username` (string, required): Alamat email akun user.
  - `password` (string, required): Password user.
  - `_csrf` (string, required): CSRF token sesi 64-karakter hex.
- **Respon Sukses:** HTTP 302 ke intended URL atau `/dashboard` dengan cookie session `PHPSESSID`.
- **Respon Gagal:** HTTP 302 ke `/login` dengan session flash error `Kredensial tidak valid.` atau `Akun Anda sedang dinonaktifkan...`

#### 2.3 Simpan Pegawai Baru (`POST /pegawai/simpan`)
- **Content-Type:** `application/x-www-form-urlencoded`
- **Body Parameter:**
  - `nip` (string, required, unik)
  - `nama_lengkap` (string, required)
  - `jabatan` (string, required)
  - `status` (enum: `aktif` | `nonaktif`, optional, default: `aktif`)
  - `_csrf` (string, required)
- **Respon:** HTTP 302 Redirect ke `/pegawai`.

#### 2.4 Update Status Cuti (`POST /cuti/status`)
- **Content-Type:** `application/x-www-form-urlencoded`
- **Body Parameter:**
  - `id` (integer, required): ID pengajuan cuti.
  - `status` (string, required: `APPROVED` atau `REJECTED`).
  - `_csrf` (string, required)
- **Respon:** HTTP 302 Redirect ke `/cuti`.
