<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Sanitasi output HTML untuk pencegahan XSS.
 */
function e(?string $str): string {
    return htmlspecialchars($str ?? '', ENT_QUOTES, 'UTF-8');
}

/**
 * Menghasilkan CSRF token per session.
 */
function csrf_token(): string {
    if (empty($_SESSION['_csrf'])) {
        $_SESSION['_csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['_csrf'];
}

/**
 * Mencetak field input hidden CSRF.
 */
function csrf_field(): string {
    $token = csrf_token();
    return '<input type="hidden" name="_csrf" value="' . e($token) . '">';
}

/**
 * Verifikasi token CSRF dari POST request.
 */
function csrf_verify(): void {
    $token = $_POST['_csrf'] ?? '';
    if (empty($token) || empty($_SESSION['_csrf']) || !hash_equals($_SESSION['_csrf'], $token)) {
        http_response_code(403);
        die("403 Forbidden: Invalid or missing CSRF token.");
    }
}

/**
 * Simpan flash message ke session.
 */
function flash_set(string $key, string $message): void {
    if (!isset($_SESSION['flash'])) {
        $_SESSION['flash'] = [];
    }
    $_SESSION['flash'][$key] = $message;
}

/**
 * Ambil dan hapus flash message dari session.
 */
function flash_get(string $key): ?string {
    if (isset($_SESSION['flash'][$key])) {
        $msg = $_SESSION['flash'][$key];
        unset($_SESSION['flash'][$key]);
        return $msg;
    }
    return null;
}

/**
 * Redirect ke URL tujuan dan hentikan proses.
 */
function redirect(string $url): void {
    header("Location: {$url}");
    exit;
}
