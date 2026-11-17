# Aplikasi Kepegawaian PT INIX (Monolitik PHP Native + MySQL & Eduparx Theme)

Aplikasi Web Kepegawaian & Pelatihan PT INIX dibangun dengan **Arsitektur Monolitik PHP Native (PDO MySQL, SSR MVC)** dan antarmuka bertema modern menyerupai **Eduparx Training Catalog**.

Aplikasi ini disiapkan khusus untuk mendukung kurikulum pelatihan Software Testing: Chrome DevTools, Postman, DBeaver, Playwright, k6, OWASP ZAP, SonarQube, Semgrep, dan GitHub Actions.

---

## 1. Quick Start One-Command

Jalankan container menggunakan Docker Compose:

```bash
docker-compose up -d --build
```

Setelah container sehat, jalankan database seeder:

```bash
docker exec -it inix_monolith_container php database/seed.php
```

---

## 2. URL Akses Aplikasi

- **Aplikasi Web (Eduparx UI):** [http://localhost:8000](http://localhost:8000)
- **Health Check Endpoint:** [http://localhost:8000/health](http://localhost:8000/health)
- **Database MySQL Port:** `localhost:3306` (User: `root`, Password: `rootpassword`, DB: `inix_db`)

---

## 3. Demo Akun Pengujian (Seed Data)

| Role | Username | Password | Hak Akses |
|---|---|---|---|
| **Admin** | `admin@inix.com` | `Admin@123!` | Akses penuh CRUD, Hapus pegawai |
| **HR** | `hr@inix.com` | `Hr@123!` | Read/Create/Update pegawai, review cuti |
| **Manager** | `manager@inix.com` | `Manager@123!` | Persetujuan Cuti (Approve/Reject) |
| **Pegawai** | `pegawai@inix.com` | `Pegawai@123!` | Mengajukan cuti, melihat sisa kuota cuti |

---

## 4. Panduan Pengujian Tools Testing

### A. Chrome DevTools
- Buka tab **Network**: periksa respon SSR HTML dokumen dan submit form POST.
- Buka tab **Application** > Cookies: periksa cookie session PHP `PHPSESSID`.
- Buka tab **Console**: verifikasi kebersihan tanpa unhandled exceptions.

### B. Postman
- Testing POST login ke `/login` (dengan form body `username`, `password`, dan `_csrf`).
- Manfaatkan fitur Postman Cookie Jar untuk otomatis menyimpan sesi autentikasi.

### C. Playwright (Automation Testing)
- Seluruh elemen penting memiliki atribut `data-testid`:
  - Login: `data-testid="input-username"`, `data-testid="input-password"`, `data-testid="btn-login"`.
  - Pegawai: `data-testid="pegawai-table"`, `data-testid="search-pegawai"`, `data-testid="btn-tambah"`, `data-testid="btn-confirm-delete"`.
  - Cuti: `data-testid="cuti-table"`, `data-testid="btn-ajukan-cuti"`, `data-testid="sisa-kuota-cuti"`.

### D. k6 (Performance Testing)
- Uji beban rendering halaman monolitik (e.g. GET `/pegawai`, GET `/dashboard`) dengan concurrent users.

### E. OWASP ZAP (Security Testing)
- Crawl formulir monolitik dan deteksi identifikasi server header default.

### F. DBeaver / MySQL Client
- Hubungkan DBeaver ke `localhost:3306` untuk verifikasi integritas relasi `users`, `pegawai`, dan `cuti`.
