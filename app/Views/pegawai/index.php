<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<section class="training-page-header">
    <div class="eduparx-container">
        <div class="breadcrumb-nav">Home &gt; Training Catalog &gt; AI Engineer &amp; Kepegawaian</div>
        <h1 class="hero-title">Katalog Pelatihan &amp; Kepegawaian PT INIX</h1>
        <p class="hero-subtitle">Kelola data talenta, pengembangan kompetensi AI Engineer, dan pengajuan cuti pegawai internal PT Inixindo.</p>
        
        <div class="hero-meta-pills">
            <span class="meta-pill">⭐ 4.9 (520+ Ulasan)</span>
            <span class="meta-pill">⏱️ 4 Hari Pelatihan (32 JP)</span>
            <span class="meta-pill">🎯 Level: Foundation to Advanced</span>
            <span class="meta-pill">💼 Inixindo Certified Professional</span>
        </div>
    </div>
</section>

<div class="eduparx-container">
    <div class="catalog-layout">
        <!-- Sidebar Filter Kiri -->
        <aside class="catalog-filter-box">
            <h3 class="filter-title">Filter Pegawai &amp; Kursus</h3>
            <form method="GET" action="/pegawai">
                <div class="filter-group">
                    <label for="search" class="form-label">Cari Nama / NIP</label>
                    <input 
                        type="text" 
                        name="search" 
                        id="search" 
                        class="form-control" 
                        placeholder="Ketik kata kunci..." 
                        value="<?= e($search) ?>"
                        data-testid="search-pegawai"
                    >
                </div>

                <div class="filter-group">
                    <label for="status" class="form-label">Status Pegawai</label>
                    <select name="status" id="status" class="form-select" data-testid="filter-status">
                        <option value="">Semua Status</option>
                        <option value="aktif" <?= $status === 'aktif' ? 'selected' : '' ?>>Aktif</option>
                        <option value="nonaktif" <?= $status === 'nonaktif' ? 'selected' : '' ?>>Non-Aktif</option>
                    </select>
                </div>

                <div class="filter-group">
                    <label for="jabatan" class="form-label">Bidang / Jabatan</label>
                    <input 
                        type="text" 
                        name="jabatan" 
                        id="jabatan" 
                        class="form-control" 
                        placeholder="e.g. AI Engineer, HR" 
                        value="<?= e($jabatan) ?>"
                        data-testid="filter-jabatan"
                    >
                </div>

                <div style="display: flex; gap: 8px;">
                    <button type="submit" class="btn btn-primary btn-sm" style="flex: 1;" data-testid="btn-filter-apply">Terapkan</button>
                    <a href="/pegawai" class="btn btn-outline btn-sm" data-testid="btn-filter-reset">Reset</a>
                </div>
            </form>
        </aside>

        <!-- Area Konten Kanan -->
        <section class="content-area">
            <div class="content-toolbar">
                <div class="content-counter" data-testid="total-counter">
                    Menampilkan <?= count($pegawais) ?> Data Pegawai
                </div>

                <div style="display: flex; gap: 12px; align-items: center;">
                    <?php if (in_array($currentUser['role'], ['hr', 'admin'], true)): ?>
                        <a href="/pegawai/tambah" class="btn btn-primary btn-sm" data-testid="btn-tambah">
                            + Tambah Pegawai
                        </a>
                    <?php endif; ?>

                    <div class="view-toggle-btns">
                        <button type="button" class="btn btn-sm btn-outline" id="btn-view-grid" data-testid="toggle-grid">Grid</button>
                        <button type="button" class="btn btn-sm btn-primary" id="btn-view-table" data-testid="toggle-table">Tabel</button>
                    </div>
                </div>
            </div>

            <!-- Card Grid View -->
            <div class="eduparx-card-grid" id="view-grid-container" style="display: none;">
                <?php foreach ($pegawais as $p): ?>
                <div class="course-card" data-testid="card-pegawai-<?= e($p['id']) ?>">
                    <div class="card-thumbnail">
                        <?= strtoupper(substr($p['nama_lengkap'], 0, 1)) ?>
                        <span class="card-category-badge"><?= e($p['jabatan']) ?></span>
                    </div>
                    <div class="card-body">
                        <div class="card-title"><?= e($p['nama_lengkap']) ?></div>
                        <div class="card-meta-row"><strong>NIP:</strong> <?= e($p['nip']) ?></div>
                        <div class="card-meta-row">
                            <strong>Status:</strong> 
                            <span class="badge badge-<?= e($p['status']) ?>"><?= ucfirst(e($p['status'])) ?></span>
                        </div>
                        <div class="card-meta-row"><strong>Sisa Cuti:</strong> <?= e($p['kuota_cuti']) ?> Hari</div>

                        <div class="card-footer-actions">
                            <?php if (in_array($currentUser['role'], ['hr', 'admin'], true)): ?>
                                <a href="/pegawai/edit?id=<?= e($p['id']) ?>" class="btn btn-outline btn-sm" data-testid="btn-edit-<?= e($p['id']) ?>">Edit</a>
                            <?php endif; ?>

                            <?php if ($currentUser['role'] === 'admin'): ?>
                                <button 
                                    type="button" 
                                    class="btn btn-danger btn-sm btn-trigger-delete" 
                                    data-id="<?= e($p['id']) ?>"
                                    data-name="<?= e($p['nama_lengkap']) ?>"
                                    data-testid="btn-delete-<?= e($p['id']) ?>"
                                >
                                    Hapus
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <!-- Data Table View -->
            <div class="eduparx-table-container" id="view-table-container">
                <table class="eduparx-table" data-testid="pegawai-table">
                    <thead>
                        <tr>
                            <th>NIP</th>
                            <th>Nama Lengkap</th>
                            <th>Jabatan</th>
                            <th>Status</th>
                            <th>Kuota Cuti</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($pegawais)): ?>
                        <tr>
                            <td colspan="6" style="text-align: center; color: var(--eduparx-text); padding: 30px;">
                                Tidak ada data pegawai yang sesuai dengan filter.
                            </td>
                        </tr>
                        <?php endif; ?>

                        <?php foreach ($pegawais as $p): ?>
                        <tr data-testid="row-pegawai-<?= e($p['id']) ?>">
                            <td style="font-weight: 600;"><?= e($p['nip']) ?></td>
                            <td><?= e($p['nama_lengkap']) ?></td>
                            <td><?= e($p['jabatan']) ?></td>
                            <td>
                                <span class="badge badge-<?= e($p['status']) ?>">
                                    <?= ucfirst(e($p['status'])) ?>
                                </span>
                            </td>
                            <td><?= e($p['kuota_cuti']) ?> Hari</td>
                            <td>
                                <div style="display: flex; gap: 6px;">
                                    <?php if (in_array($currentUser['role'], ['hr', 'admin'], true)): ?>
                                        <a href="/pegawai/edit?id=<?= e($p['id']) ?>" class="btn btn-outline btn-sm" data-testid="btn-edit-<?= e($p['id']) ?>">Edit</a>
                                    <?php endif; ?>

                                    <?php if ($currentUser['role'] === 'admin'): ?>
                                        <button 
                                            type="button" 
                                            class="btn btn-danger btn-sm btn-trigger-delete" 
                                            data-id="<?= e($p['id']) ?>"
                                            data-name="<?= e($p['nama_lengkap']) ?>"
                                            data-testid="btn-delete-<?= e($p['id']) ?>"
                                        >
                                            Hapus
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </div>
</div>

<?php 
require_once __DIR__ . '/../layouts/confirm_dialog.php';
require_once __DIR__ . '/../layouts/footer.php'; 
?>
