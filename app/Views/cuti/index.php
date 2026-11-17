<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<section class="training-page-header">
    <div class="eduparx-container">
        <div class="breadcrumb-nav">Home &gt; Pengajuan Cuti</div>
        <h1 class="hero-title">Pengelolaan Cuti Pegawai PT INIX</h1>
        <p class="hero-subtitle">Transparansi permohonan cuti, sisa kuota hak cuti tahunan, dan verifikasi persetujuan pimpinan.</p>
    </div>
</section>

<div class="eduparx-container" style="margin-top: 30px; margin-bottom: 50px;">
    <?php if ($isPegawai && $kuotaInfo): ?>
    <div class="stat-grid">
        <div class="stat-card">
            <div class="stat-number" data-testid="kuota-tahunan"><?= e($kuotaInfo['kuota_tahunan']) ?> Hari</div>
            <div class="stat-label">Hak Kuota Tahunan</div>
        </div>
        <div class="stat-card">
            <div class="stat-number" data-testid="cuti-terpakai"><?= e($kuotaInfo['terpakai']) ?> Hari</div>
            <div class="stat-label">Cuti Terpakai (Approved)</div>
        </div>
        <div class="stat-card">
            <div class="stat-number" data-testid="sisa-kuota-cuti" style="color: var(--eduparx-accent);"><?= e($kuotaInfo['sisa_kuota']) ?> Hari</div>
            <div class="stat-label">Sisa Kuota Cuti Anda</div>
        </div>
    </div>
    <?php endif; ?>

    <div class="content-toolbar">
        <div class="content-counter">
            Daftar Pengajuan Cuti (<?= count($cutis) ?> Data)
        </div>

        <?php if ($isPegawai): ?>
            <a href="/cuti/ajukan" class="btn btn-primary btn-sm" data-testid="btn-ajukan-cuti">
                + Ajukan Cuti Baru
            </a>
        <?php endif; ?>
    </div>

    <div class="eduparx-table-container">
        <table class="eduparx-table" data-testid="cuti-table">
            <thead>
                <tr>
                    <th>Pemohon</th>
                    <th>NIP</th>
                    <th>Tanggal Mulai</th>
                    <th>Tanggal Selesai</th>
                    <th>Alasan</th>
                    <th>Status</th>
                    <?php if ($currentUser['role'] === 'manajer'): ?>
                        <th>Aksi Verifikasi</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($cutis)): ?>
                <tr>
                    <td colspan="<?= ($currentUser['role'] === 'manajer') ? 7 : 6 ?>" style="text-align: center; color: var(--eduparx-text); padding: 30px;">
                        Belum ada riwayat permohonan cuti.
                    </td>
                </tr>
                <?php endif; ?>

                <?php foreach ($cutis as $c): ?>
                <tr data-testid="row-cuti-<?= e($c['id']) ?>">
                    <td style="font-weight: 600;"><?= e($c['nama_lengkap']) ?></td>
                    <td><?= e($c['nip']) ?></td>
                    <td><?= e($c['tgl_mulai']) ?></td>
                    <td><?= e($c['tgl_selesai']) ?></td>
                    <td><?= e($c['alasan']) ?></td>
                    <td>
                        <span class="badge badge-<?= strtolower(e($c['status'])) ?>" data-testid="status-cuti-<?= e($c['id']) ?>">
                            <?= e($c['status']) ?>
                        </span>
                    </td>
                    <?php if ($currentUser['role'] === 'manajer'): ?>
                    <td>
                        <?php if ($c['status'] === 'PENDING'): ?>
                            <div style="display: flex; gap: 6px;">
                                <form method="POST" action="/cuti/status" style="margin: 0;">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="id" value="<?= e($c['id']) ?>">
                                    <input type="hidden" name="status" value="APPROVED">
                                    <button type="submit" class="btn btn-success btn-sm" data-testid="btn-approve-<?= e($c['id']) ?>">Setujui</button>
                                </form>
                                <form method="POST" action="/cuti/status" style="margin: 0;">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="id" value="<?= e($c['id']) ?>">
                                    <input type="hidden" name="status" value="REJECTED">
                                    <button type="submit" class="btn btn-danger btn-sm" data-testid="btn-reject-<?= e($c['id']) ?>">Tolak</button>
                                </form>
                            </div>
                        <?php else: ?>
                            <span style="font-size: 0.82rem; color: #94a3b8;">Telah Diverifikasi</span>
                        <?php endif; ?>
                    </td>
                    <?php endif; ?>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
