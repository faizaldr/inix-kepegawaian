# GAP ANALYSIS & VALIDATION REPORT
## Sistem Informasi Kepegawaian & Pelatihan PT INIX

Dokumen ini memvalidasi kesesuaian antara dokumen warisan awal (`DOKUMEN_FRD_(SRS)_FINAL.md`), master spesifikasi (`MASTER_PROMPT.md`), dan **implementasi aktual source code (AS-IS)** di dalam repositori.

---

### 1. Ringkasan Status Validasi & Kategori Temuan

Setiap temuan diklasifikasikan ke dalam 3 status:
- **Verified:** Fitur/aturan ada dalam kode sumber dan selaras secara teknis.
- **Partially Verified:** Fitur ada tetapi memiliki perbedaan implementasi teknis dibandingkan deskripsi dokumen warisan.
- **TBD / Missing In Code / Discrepancy:** Informasi yang terdapat dalam dokumen lama namun tidak sesuai atau tidak dijumpai dalam source code aktual.

---

### 2. Matriks Analisis Kesenjangan (Gap Analysis Matrix)

| No | Komponen / Area | Kondisi dalam Dokumen Lama (`DOKUMEN_FRD_(SRS)_FINAL.md`) | Kondisi Aktual dalam Source Code (AS-IS) | Status | Dampak & Rekomendasi Mitigasi |
|---|---|---|---|---|---|
| **GAP-01** | **Pola Arsitektur & File Controller** | Menyebutkan arsitektur *Page-Controller* berbasis file individual: `login.php`, `pegawai.php`, `cuti.php` (Bagian 2.1 & Bagian 6). | Sistem menggunakan arsitektur **Front Controller MVC** penuh: `public/index.php` -> `includes/Router.php` -> `app/Controllers/*` -> `app/Models/*`. Tidak ada file `login.php` dkk di root. | **Verified (Discrepancy Documented)** | **Tinggi:** Dokumen lama usang. Dokumentasi baru (FRD/SRS v2.0) wajib merefleksikan MVC Front Controller aktual. |
| **GAP-02** | **Desain Sistem & Antarmuka UI** | Dokumen lama menyebutkan pemakaian framework template **Bootstrap / AdminLTE** (Bagian 2.1 & 4 NFR-003). | Sistem mengimplementasikan **Pure CSS Eduparx Design System** kustom (`public/assets/css/style.css`) bertema Eduparx Training Catalog Inixindo (tanpa AdminLTE/Bootstrap CSS). | **Verified (Discrepancy Documented)** | **Sedang:** Tidak menggunakan library eksternal CSS, melainkan native CSS modular variables. Dokumen SRS diperbarui ke Eduparx Theme. |
| **GAP-03** | **Versi Lingkungan PHP Runtime** | Dokumen lama menyatakan sistem berjalan pada **PHP Native v8.5.x** (Bagian 2.1). | `Dockerfile` mengonfigurasi basis image resmi **`php:8.2-apache`**; `docker-compose.yml` memakai environment PHP 8.2. | **Verified (Discrepancy Documented)** | **Rendah:** Spesifikasi dokumen lama tidak realistis (PHP 8.5 belum rilis sebagai versi stabil saat build). Diperbaiki ke PHP 8.2. |
| **GAP-04** | **Routing & Endpoint URL** | Dokumen lama menyebutkan URL berbentuk skrip dan query parameter: `pegawai.php?action=create`, `cuti.php?action=request`. | Sistem menggunakan clean URL: `GET /pegawai/tambah`, `POST /pegawai/simpan`, `GET /cuti/ajukan`, `POST /cuti/simpan`, `POST /cuti/status`. | **Verified (Discrepancy Documented)** | **Tinggi:** Seluruh automated QA script Playwright dan Postman mengacu pada clean URL MVC. |
| **GAP-05** | **Endpoint Health Check (`/health`)** | Belum tercatat / belum didokumentasikan dalam FRD/SRS lama. | Diimplementasikan secara eksplisit pada `public/index.php` (line 31-39) untuk docker healthcheck & CI/CD. | **Verified (Missing in Old Doc)** | **Sedang:** Ditambahkan ke dalam SRS dan RTM sebagai requirement resmi FR-012. |
| **GAP-06** | **Penamaan Role Manajer (RBAC)** | Dicantumkan dengan ejaan Bahasa Inggris `Manager` pada beberapa tabel dan `manajer` pada tabel lain. | Skema DDL `schema.sql` menetapkan ENUM secara ketat: `'admin','hr','manajer','pegawai'`. Aktor adalah `manajer`. | **Partially Verified** | **Rendah:** Diseragamkan dalam FRD/SRS menjadi role resmi `manajer`. |
| **GAP-07** | **Logika Perhitungan Sisa Kuota Cuti** | Dokumen lama menyatakan kuota pegawai di database berkurang secara statis saat cuti disetujui. | `app/Models/Cuti.php::getKuotaInfo` menghitung sisa kuota secara **dinamis** berdasarkan akumulasi selisih hari (`diff->days + 1`) cuti `APPROVED` pada tahun berjalan terhadap `pegawai.kuota_cuti`. | **Verified (Enhancement over Doc)** | **Sedang:** Logika dinamis lebih robust karena mencegah ketidaksinkronan data jika status diubah atau dibatalkan. |
| **GAP-08** | **Fitur View Toggle (Card Grid ↔ Table)** | Dokumen lama hanya mendokumentasikan tabel pegawai biasa. | `app/Views/pegawai/index.php` menyediakan tombol toggle view antara Eduparx Card Grid dan Data Table View. | **Verified (Missing in Old Doc)** | **Rendah:** Didokumentasikan sebagai fitur usability pada katalog pegawai. |
| **GAP-09** | **Foreign Key Constraint Integrity** | Belum didetailkan secara rinci perilaku cascading. | `database/schema.sql`: `pegawai.user_id` memakai `ON DELETE SET NULL`, sedangkan `cuti.pegawai_id` memakai `ON DELETE CASCADE`. | **Verified** | **Penting:** Menjamin integritas referensial data personalia dan permohonan cuti. |
| **GAP-10** | **Spesifikasi Rate Limiting / WAF** | Tidak disebutkan pembatasan brute-force pada login. | Source code `AuthController.php` belum mengimplementasikan rate limiting login atau captcha (sengaja untuk kemudahan pengujian k6 & OWASP ZAP). | **TBD / Not Implemented** | **Rekomendasi:** Diberikan catatan batasan sistem (system constraints) bahwa brute-force protection sengaja tidak diimplementasikan untuk tujuan training testing. |

---

### 3. Rekomendasi Baseline Sistem
1. Memperbarui dokumen FRD dan SRS agar mengacu 100% pada kondisi aktual arsitektur **Front Controller MVC Monolitik**.
2. Menggantikan seluruh referensi legacy `*.php?action=...` menjadi clean routing standard `/pegawai/*` dan `/cuti/*`.
3. Menetapkan spesifikasi antarmuka resmi sebagai **Eduparx Pure CSS Theme**.
