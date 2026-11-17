<?php

require_once __DIR__ . '/../../config/database.php';

class Pegawai {
    public static function getAll(string $search = '', string $status = '', string $jabatan = ''): array {
        $pdo = Database::getConnection();
        $sql = "SELECT p.*, u.username as user_username 
                FROM pegawai p 
                LEFT JOIN users u ON p.user_id = u.id 
                WHERE 1=1";
        $params = [];

        if (!empty($search)) {
            $sql .= " AND (p.nama_lengkap LIKE ? OR p.nip LIKE ?)";
            $params[] = "%{$search}%";
            $params[] = "%{$search}%";
        }

        if (!empty($status)) {
            $sql .= " AND p.status = ?";
            $params[] = $status;
        }

        if (!empty($jabatan)) {
            $sql .= " AND p.jabatan LIKE ?";
            $params[] = "%{$jabatan}%";
        }

        $sql .= " ORDER BY p.id ASC";

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public static function getById(int $id): ?array {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("SELECT * FROM pegawai WHERE id = ? LIMIT 1");
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public static function getByNip(string $nip): ?array {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("SELECT * FROM pegawai WHERE nip = ? LIMIT 1");
        $stmt->execute([$nip]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public static function getByUserId(int $userId): ?array {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("SELECT * FROM pegawai WHERE user_id = ? LIMIT 1");
        $stmt->execute([$userId]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public static function create(array $data): bool {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("INSERT INTO pegawai (user_id, nip, nama_lengkap, jabatan, status, kuota_cuti) VALUES (?, ?, ?, ?, ?, ?)");
        return $stmt->execute([
            $data['user_id'] ?? null,
            $data['nip'],
            $data['nama_lengkap'],
            $data['jabatan'],
            $data['status'] ?? 'aktif',
            $data['kuota_cuti'] ?? 12
        ]);
    }

    public static function update(int $id, array $data): bool {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("UPDATE pegawai SET nip = ?, nama_lengkap = ?, jabatan = ?, status = ? WHERE id = ?");
        return $stmt->execute([
            $data['nip'],
            $data['nama_lengkap'],
            $data['jabatan'],
            $data['status'],
            $id
        ]);
    }

    public static function delete(int $id): bool {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("DELETE FROM pegawai WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public static function countAktif(): int {
        $pdo = Database::getConnection();
        $stmt = $pdo->query("SELECT COUNT(*) FROM pegawai WHERE status = 'aktif'");
        return (int) $stmt->fetchColumn();
    }
}
