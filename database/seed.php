<?php

require_once __DIR__ . '/../config/database.php';

try {
    $pdo = Database::getConnection();

    // 1. Jalankan schema.sql
    $sql = file_get_contents(__DIR__ . '/schema.sql');
    $pdo->exec($sql);

    // Bersihkan data lama jika ada
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 0;");
    $pdo->exec("TRUNCATE TABLE cuti;");
    $pdo->exec("TRUNCATE TABLE pegawai;");
    $pdo->exec("TRUNCATE TABLE users;");
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 1;");

    // 2. Insert users
    $users = [
        ['admin@inix.com', password_hash('Admin@123!', PASSWORD_DEFAULT), 'admin', 1],
        ['hr@inix.com', password_hash('Hr@123!', PASSWORD_DEFAULT), 'hr', 1],
        ['manager@inix.com', password_hash('Manager@123!', PASSWORD_DEFAULT), 'manajer', 1],
        ['pegawai@inix.com', password_hash('Pegawai@123!', PASSWORD_DEFAULT), 'pegawai', 1],
    ];

    $stmtUser = $pdo->prepare("INSERT INTO users (username, password, role, is_active) VALUES (?, ?, ?, ?)");
    foreach ($users as $u) {
        $stmtUser->execute($u);
    }

    // Ambil ID user untuk referensi
    $stmtFind = $pdo->prepare("SELECT id FROM users WHERE username = ?");
    $getUserId = function($uname) use ($stmtFind) {
        $stmtFind->execute([$uname]);
        $row = $stmtFind->fetch();
        return $row ? $row['id'] : null;
    };

    $hrUserId = $getUserId('hr@inix.com');
    $mgrUserId = $getUserId('manager@inix.com');
    $pegawaiUserId = $getUserId('pegawai@inix.com');

    // 3. Insert pegawai
    $pegawais = [
        [$hrUserId, 'NIP-2025-001', 'Andi Wijaya', 'Staff HR', 'aktif', 12],
        [$mgrUserId, 'NIP-2025-002', 'Siti Rahayu', 'Manajer IT', 'aktif', 12],
        [null, 'NIP-2025-003', 'Rudi Hermawan', 'Lead QA Automation', 'nonaktif', 12],
        [$pegawaiUserId, 'NIP-2025-004', 'Budi Santoso', 'AI Engineer', 'aktif', 12],
        [null, 'NIP-2025-005', 'Maya Putri', 'Staff Data Science', 'aktif', 12],
    ];

    $stmtPegawai = $pdo->prepare("INSERT INTO pegawai (user_id, nip, nama_lengkap, jabatan, status, kuota_cuti) VALUES (?, ?, ?, ?, ?, ?)");
    foreach ($pegawais as $p) {
        $stmtPegawai->execute($p);
    }

    // 4. Insert cuti
    $cutis = [
        [4, '2026-12-01', '2026-12-03', 'Mengikuti Konferensi AI Inixindo', 'PENDING'],
        [5, '2026-11-20', '2026-11-21', 'Sakit', 'APPROVED']
    ];

    $stmtCuti = $pdo->prepare("INSERT INTO cuti (pegawai_id, tgl_mulai, tgl_selesai, alasan, status) VALUES (?, ?, ?, ?, ?)");
    foreach ($cutis as $c) {
        $stmtCuti->execute($c);
    }

    echo "[SUCCESS] Inix database seeded successfully.\n";
} catch (Exception $e) {
    echo "[ERROR] Seeding failed: " . $e->getMessage() . "\n";
    exit(1);
}
