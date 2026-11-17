document.addEventListener('DOMContentLoaded', () => {
    // 1. View Toggle: Card Grid <-> Data Table
    const btnGridView = document.getElementById('btn-view-grid');
    const btnTableView = document.getElementById('btn-view-table');
    const containerGrid = document.getElementById('view-grid-container');
    const containerTable = document.getElementById('view-table-container');

    if (btnGridView && btnTableView && containerGrid && containerTable) {
        const setView = (mode) => {
            if (mode === 'table') {
                containerGrid.style.display = 'none';
                containerTable.style.display = 'block';
                btnTableView.classList.add('btn-primary');
                btnTableView.classList.remove('btn-outline');
                btnGridView.classList.add('btn-outline');
                btnGridView.classList.remove('btn-primary');
            } else {
                containerGrid.style.display = 'grid';
                containerTable.style.display = 'none';
                btnGridView.classList.add('btn-primary');
                btnGridView.classList.remove('btn-outline');
                btnTableView.classList.add('btn-outline');
                btnTableView.classList.remove('btn-primary');
            }
            localStorage.setItem('eduparx_view_mode', mode);
        };

        const savedMode = localStorage.getItem('eduparx_view_mode') || 'table';
        setView(savedMode);

        btnGridView.addEventListener('click', () => setView('grid'));
        btnTableView.addEventListener('click', () => setView('table'));
    }

    // 2. Modal Dialog Konfirmasi Hapus
    const modalBackdrop = document.getElementById('delete-modal');
    const deleteIdInput = document.getElementById('delete-target-id');
    const deleteNamaText = document.getElementById('delete-target-name');
    const btnCancelDelete = document.getElementById('btn-cancel-delete');

    document.querySelectorAll('.btn-trigger-delete').forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            const id = btn.getAttribute('data-id');
            const name = btn.getAttribute('data-name');
            if (deleteIdInput) deleteIdInput.value = id;
            if (deleteNamaText) deleteNamaText.textContent = name;
            if (modalBackdrop) modalBackdrop.style.display = 'flex';
        });
    });

    if (btnCancelDelete && modalBackdrop) {
        btnCancelDelete.addEventListener('click', () => {
            modalBackdrop.style.display = 'none';
        });
    }

    if (modalBackdrop) {
        modalBackdrop.addEventListener('click', (e) => {
            if (e.target === modalBackdrop) {
                modalBackdrop.style.display = 'none';
            }
        });
    }

    // 3. Hitung Durasi Cuti Otomatis
    const tglMulaiInput = document.querySelector('[data-testid="input-tgl-mulai"]');
    const tglSelesaiInput = document.querySelector('[data-testid="input-tgl-selesai"]');
    const durasiSpan = document.querySelector('[data-testid="durasi-cuti"]');

    if (tglMulaiInput && tglSelesaiInput && durasiSpan) {
        const calcDuration = () => {
            const startVal = tglMulaiInput.value;
            const endVal = tglSelesaiInput.value;

            if (startVal && endVal) {
                const startDate = new Date(startVal);
                const endDate = new Date(endVal);

                if (endDate >= startDate) {
                    const diffTime = Math.abs(endDate - startDate);
                    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;
                    durasiSpan.textContent = `${diffDays} Hari Kerja`;
                } else {
                    durasiSpan.textContent = 'Tanggal tidak valid';
                }
            } else {
                durasiSpan.textContent = '0 Hari';
            }
        };

        tglMulaiInput.addEventListener('change', calcDuration);
        tglSelesaiInput.addEventListener('change', calcDuration);
    }
});
