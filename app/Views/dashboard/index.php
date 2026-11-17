<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<section class="training-page-header">
    <div class="eduparx-container">
        <div class="breadcrumb-nav">Home &gt; Dashboard</div>
        <h1 class="hero-title" data-testid="welcome-msg">Selamat Datang, <?= e($user['username']) ?>!</h1>
        <p class="hero-subtitle">Portal Manajemen Kepegawaian & Pusat Pelatihan Talenta PT Inixindo.</p>
        
        <div class="hero-meta-pills">
            <span class="meta-pill">⭐ 4.9 (520+ Ulasan Alumni)</span>
            <span class="meta-pill">⏱️ 4 Hari Pelatihan (32 JP)</span>
            <span class="meta-pill">🎯 Level: Foundation to Advanced</span>
            <span class="meta-pill">💼 Inixindo Certified Professional</span>
        </div>
    </div>
</section>

<div class="eduparx-container" style="margin-top: 30px; margin-bottom: 50px;">
    <div class="stat-grid">
        <div class="stat-card" data-testid="card-pegawai-aktif">
            <div class="stat-number" data-testid="stat-pegawai-aktif"><?= e($totalPegawaiAktif) ?></div>
            <div class="stat-label">Total Pegawai Aktif</div>
        </div>
        <div class="stat-card" data-testid="card-cuti-pending">
            <div class="stat-number" data-testid="stat-cuti-pending"><?= e($totalCutiPending) ?></div>
            <div class="stat-label">Pengajuan Cuti Menunggu Persetujuan</div>
        </div>
        <div class="stat-card">
            <div class="stat-number"><?= strtoupper(e($user['role'])) ?></div>
            <div class="stat-label">Hak Akses Role Anda</div>
        </div>
    </div>

    <div style="display: flex; gap: 14px; flex-wrap: wrap;">
        <a href="/pegawai" class="btn btn-primary" data-testid="quick-btn-pegawai">Lihat Katalog Pegawai</a>
        <a href="/cuti" class="btn btn-outline" data-testid="quick-btn-cuti">Kelola Pengajuan Cuti</a>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
