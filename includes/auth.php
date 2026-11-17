<?php

require_once __DIR__ . '/helpers.php';

/**
 * Mendapatkan data session user yang sedang aktif.
 */
function auth_user(): ?array {
    return $_SESSION['user'] ?? null;
}

/**
 * Mengecek apakah user saat ini telah terautentikasi.
 */
function auth_check(): bool {
    return isset($_SESSION['user']) && !empty($_SESSION['user']['id']);
}

/**
 * Memastikan user sudah login sebelum melanjutkan.
 */
function require_auth(): void {
    if (!auth_check()) {
        $_SESSION['intended_url'] = $_SERVER['REQUEST_URI'] ?? '/dashboard';
        flash_set('error', 'Silakan masuk terlebih dahulu untuk mengakses sistem.');
        redirect('/login');
    }
}

/**
 * Membatasi akses berdasarkan satu atau lebih peran (RBAC).
 */
function require_role(...$allowedRoles): void {
    require_auth();
    $user = auth_user();
    if (!in_array($user['role'] ?? '', $allowedRoles, true)) {
        http_response_code(403);
        flash_set('error', 'Anda tidak memiliki hak akses untuk tindakan ini.');
        redirect('/dashboard');
    }
}

/**
 * Menyimpan data autentikasi user ke session.
 */
function auth_login(array $user): void {
    session_regenerate_id(true);
    $_SESSION['user'] = [
        'id'       => $user['id'],
        'username' => $user['username'],
        'role'     => $user['role']
    ];
}

/**
 * Membersihkan sesi dan keluar dari sistem.
 */
function auth_logout(): void {
    $_SESSION = [];
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(
            session_name(),
            '',
            time() - 42000,
            $params["path"],
            $params["domain"],
            $params["secure"],
            $params["httponly"]
        );
    }
    session_destroy();
}
