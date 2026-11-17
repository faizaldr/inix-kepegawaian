<div class="modal-backdrop" id="delete-modal" role="alertdialog" aria-modal="true" aria-labelledby="modal-title">
    <div class="modal-content">
        <h3 id="modal-title" style="margin-bottom: 12px; color: var(--eduparx-dark);">Konfirmasi Hapus Data</h3>
        <p style="margin-bottom: 20px; color: var(--eduparx-text);">
            Apakah Anda yakin ingin menghapus data pegawai <strong id="delete-target-name">-</strong>? Tindakan ini tidak dapat dibatalkan.
        </p>

        <form method="POST" action="/pegawai/hapus">
            <?= csrf_field() ?>
            <input type="hidden" name="id" id="delete-target-id" value="">
            <div style="display: flex; justify-content: flex-end; gap: 10px;">
                <button type="button" class="btn btn-outline" id="btn-cancel-delete" data-testid="btn-cancel-delete">Batal</button>
                <button type="submit" class="btn btn-danger" data-testid="btn-confirm-delete">Hapus Data</button>
            </div>
        </form>
    </div>
</div>
