<?php

require_once __DIR__ . '/../../config/database.php';

class User {
    public static function findByUsername(string $username): ?array {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? LIMIT 1");
        $stmt->execute([$username]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public static function findById(int $id): ?array {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("SELECT id, username, role, is_active FROM users WHERE id = ? LIMIT 1");
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public static function getAll(): array {
        $pdo = Database::getConnection();
        $stmt = $pdo->query("SELECT id, username, role, is_active FROM users ORDER BY id ASC");
        return $stmt->fetchAll();
    }
}
