<?php
$pageTitle = 'Masuk Portal | PT INIX Eduparx';
require_once __DIR__ . '/../layouts/header.php';
?>

<div class="eduparx-container" style="max-width: 480px; margin: 40px auto 60px auto;">
    <div class="eduparx-card">
        <div style="text-align: center; margin-bottom: 24px;">
            <span class="brand-badge" style="font-size: 0.9rem; padding: 4px 12px;">PORTAL KEPEGAWAIAN</span>
            <h2 style="font-size: 1.5rem; font-weight: 800; color: var(--eduparx-dark); margin-top: 12px;">Masuk ke Akun Anda</h2>
            <p style="color: var(--eduparx-text); font-size: 0.9rem;">Sistem Informasi & Manajemen Talenta PT INIX</p>
        </div>

        <form method="POST" action="/login">
            <?= csrf_field() ?>

            <div style="margin-bottom: 16px;">
                <label for="username" class="form-label">Email / Username</label>
                <input 
                    type="text" 
                    id="username" 
                    name="username" 
                    class="form-control" 
                    placeholder="nama@inix.com" 
                    required 
                    data-testid="input-username"
                >
            </div>

            <div style="margin-bottom: 24px;">
                <label for="password" class="form-label">Password</label>
                <input 
                    type="password" 
                    id="password" 
                    name="password" 
                    class="form-control" 
                    placeholder="Masukkan password" 
                    required 
                    data-testid="input-password"
                >
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%;" data-testid="btn-login">
                Masuk ke Portal
            </button>
        </form>

        <div style="margin-top: 24px; padding: 14px; background: #f8fafd; border-radius: 6px; font-size: 0.8rem; border: 1px dashed var(--eduparx-border);">
            <strong style="display: block; margin-bottom: 6px; color: var(--eduparx-dark);">Akun Uji Coba (Testing):</strong>
            <ul style="padding-left: 16px; margin: 0; color: #64748b; line-height: 1.5;">
                <li>Admin: <code>admin@inix.com</code> / <code>Admin@123!</code></li>
                <li>HR: <code>hr@inix.com</code> / <code>Hr@123!</code></li>
                <li>Manager: <code>manager@inix.com</code> / <code>Manager@123!</code></li>
                <li>Pegawai: <code>pegawai@inix.com</code> / <code>Pegawai@123!</code></li>
            </ul>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
