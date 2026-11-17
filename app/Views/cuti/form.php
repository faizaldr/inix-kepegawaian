<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="eduparx-container" style="max-width: 600px; margin: 40px auto 60px auto;">
    <div class="eduparx-card">
        <h2 style="font-size: 1.4rem; font-weight: 800; color: var(--eduparx-dark); margin-bottom: 8px;">
            Ajukan Permohonan Cuti
        </h2>
        <p style="color: var(--eduparx-text); font-size: 0.9rem; margin-bottom: 24px;">
            Sisa kuota hak cuti Anda tahun ini: <strong style="color: var(--eduparx-primary);"><?= e($kuotaInfo['sisa_kuota']) ?> Hari</strong>.
        </p>

        <form method="POST" action="/cuti/simpan">
            <?= csrf_field() ?>

            <div style="margin-bottom: 16px;">
                <label for="tgl_mulai" class="form-label">Tanggal Mulai Cuti</label>
                <input 
                    type="date" 
                    id="tgl_mulai" 
                    name="tgl_mulai" 
                    class="form-control" 
                    required 
                    data-testid="input-tgl-mulai"
                >
            </div>

            <div style="margin-bottom: 16px;">
                <label for="tgl_selesai" class="form-label">Tanggal Selesai Cuti</label>
                <input 
                    type="date" 
                    id="tgl_selesai" 
                    name="tgl_selesai" 
                    class="form-control" 
                    required 
                    data-testid="input-tgl-selesai"
                >
            </div>

            <div style="margin-bottom: 16px; padding: 10px 14px; background: #f8fafd; border-radius: 6px; font-size: 0.9rem;">
                Estimasi Durasi: <strong data-testid="durasi-cuti" style="color: var(--eduparx-primary);">0 Hari</strong>
            </div>

            <div style="margin-bottom: 24px;">
                <label for="alasan" class="form-label">Alasan / Keperluan Cuti</label>
                <textarea 
                    id="alasan" 
                    name="alasan" 
                    rows="3" 
                    class="form-control" 
                    placeholder="Tuliskan keterangan permohonan cuti secara jelas..." 
                    required 
                    data-testid="input-alasan"
                ></textarea>
            </div>

            <div style="display: flex; gap: 12px; justify-content: flex-end;">
                <a href="/cuti" class="btn btn-outline" data-testid="btn-batal-cuti">Batal</a>
                <button type="submit" class="btn btn-primary" data-testid="btn-submit-cuti">Kirim Permohonan Cuti</button>
            </div>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
