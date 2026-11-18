<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="eduparx-container" style="max-width: 650px; margin: 40px auto 60px auto;">
    <div class="eduparx-card">
        <h2 style="font-size: 1.4rem; font-weight: 800; color: var(--eduparx-dark); margin-bottom: 20px;">
            <?= $mode === 'create' ? 'Tambah Data Pegawai Baru' : 'Perbarui Data Pegawai' ?>
        </h2>

        <form method="POST" action="<?= $mode === 'create' ? '/pegawai/simpan' : '/pegawai/update' ?>">
            <?= csrf_field() ?>
            <?php if ($mode === 'edit'): ?>
                <input type="hidden" name="id" value="<?= e($pegawai['id']) ?>">
            <?php endif; ?>

            <div style="margin-bottom: 16px;">
                <label for="nip" class="form-label">Nomor Induk Pegawai (NIP)</label>
                <input 
                    type="text" 
                    id="nip" 
                    name="nip" 
                    class="form-control" 
                    value="<?= e($pegawai['nip']) ?>" 
                    placeholder="Contoh: NIP-2025-006" 
                    required 
                    data-testid="input-nip"
                >
            </div>

            <div style="margin-bottom: 16px;">
                <label for="nama_lengkap" class="form-label">Nama Lengkap</label>
                <input 
                    type="text" 
                    id="nama_lengkap" 
                    name="nama_lengkap" 
                    class="form-control" 
                    value="<?= e($pegawai['nama_lengkap']) ?>" 
                    placeholder="Nama lengkap dan gelar" 
                    required 
                    data-testid="input-nama"
                >
            </div>

            <div style="margin-bottom: 16px;">
                <label for="jabatan" class="form-label">Jabatan / Role Pelatihan</label>
                <input 
                    type="text" 
                    id="jabatan" 
                    name="jabatan" 
                    class="form-control" 
                    value="<?= e($pegawai['jabatan']) ?>" 
                    placeholder="Contoh: AI Engineer, QA Automation" 
                    required 
                    data-testid="input-jabatan"
                >
            </div>

            <div style="margin-bottom: 24px;">
                <label for="status" class="form-label">Status Kepegawaian</label>
                <select id="status" name="status" class="form-select" data-testid="select-status">
                    <option value="aktif" <?= ($pegawai['status'] === 'aktif') ? 'selected' : '' ?>>Aktif</option>
                    <option value="nonaktif" <?= ($pegawai['status'] === 'nonaktif') ? 'selected' : '' ?>>Non-Aktif</option>
                </select>
            </div>

            <div style="display: flex; gap: 12px; justify-content: flex-end;">
                <a href="/pegawai" class="btn btn-outline" data-testid="btn-batal">Batal</a>
                <button type="submit" class="btn btn-primary" data-testid="btn-simpan">
                    <?= $mode === 'create' ? 'Simpan Data Pegawai' : 'Perbarui Data' ?>
                </button>
            </div>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
