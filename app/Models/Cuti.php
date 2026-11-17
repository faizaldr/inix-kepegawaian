<?php

require_once __DIR__ . '/../../config/database.php';

class Cuti {
    public static function getAll(?int $pegawaiId = null): array {
        $pdo = Database::getConnection();
        $sql = "SELECT c.*, p.nama_lengkap, p.nip 
                FROM cuti c 
                JOIN pegawai p ON c.pegawai_id = p.id";
        $params = [];

        if ($pegawaiId !== null) {
            $sql .= " WHERE c.pegawai_id = ?";
            $params[] = $pegawaiId;
        }

        $sql .= " ORDER BY c.created_at DESC";

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public static function getById(int $id): ?array {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("SELECT c.*, p.nama_lengkap, p.nip FROM cuti c JOIN pegawai p ON c.pegawai_id = p.id WHERE c.id = ? LIMIT 1");
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public static function getKuotaInfo(int $pegawaiId, ?string $tahun = null): array {
        $pdo = Database::getConnection();
        $tahun = $tahun ?: date('Y');

        $stmtPeg = $pdo->prepare("SELECT kuota_cuti FROM pegawai WHERE id = ?");
        $stmtPeg->execute([$pegawaiId]);
        $kuotaTahunan = (int) ($stmtPeg->fetchColumn() ?: 12);

        // Hitung total hari cuti APPROVED pada tahun berjalan
        $stmtApp = $pdo->prepare("SELECT tgl_mulai, tgl_selesai FROM cuti WHERE pegawai_id = ? AND status = 'APPROVED' AND YEAR(tgl_mulai) = ?");
        $stmtApp->execute([$pegawaiId, $tahun]);
        $approvedCutis = $stmtApp->fetchAll();

        $approvedDays = 0;
        foreach ($approvedCutis as $row) {
            $start = new DateTime($row['tgl_mulai']);
            $end = new DateTime($row['tgl_selesai']);
            $diff = $start->diff($end)->days + 1;
            $approvedDays += $diff;
        }

        // Hitung total cuti status PENDING
        $stmtPend = $pdo->prepare("SELECT COUNT(*) FROM cuti WHERE pegawai_id = ? AND status = 'PENDING' AND YEAR(tgl_mulai) = ?");
        $stmtPend->execute([$pegawaiId, $tahun]);
        $pendingCount = (int) $stmtPend->fetchColumn();

        $sisaKuota = max(0, $kuotaTahunan - $approvedDays);

        return [
            'kuota_tahunan' => $kuotaTahunan,
            'terpakai'      => $approvedDays,
            'pending_count' => $pendingCount,
            'sisa_kuota'    => $sisaKuota
        ];
    }

    public static function create(array $data): bool {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("INSERT INTO cuti (pegawai_id, tgl_mulai, tgl_selesai, alasan, status) VALUES (?, ?, ?, ?, 'PENDING')");
        return $stmt->execute([
            $data['pegawai_id'],
            $data['tgl_mulai'],
            $data['tgl_selesai'],
            $data['alasan']
        ]);
    }

    public static function updateStatus(int $id, string $status): bool {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("UPDATE cuti SET status = ? WHERE id = ?");
        return $stmt->execute([$status, $id]);
    }

    public static function countPending(): int {
        $pdo = Database::getConnection();
        $stmt = $pdo->query("SELECT COUNT(*) FROM cuti WHERE status = 'PENDING'");
        return (int) $stmt->fetchColumn();
    }
}
