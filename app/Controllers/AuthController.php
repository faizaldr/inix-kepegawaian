<?php

require_once __DIR__ . '/../../includes/helpers.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../Models/User.php';

class AuthController {
    public function showLogin(): void {
        if (auth_check()) {
            redirect('/dashboard');
        }

        $error = flash_get('error');
        $success = flash_get('success');

        require_once __DIR__ . '/../Views/auth/login.php';
    }

    public function login(): void {
        csrf_verify();

        $username = trim($_POST['username'] ?? '');
        $password = trim($_POST['password'] ?? '');

        if ($username === '' || $password === '') {
            flash_set('error', 'Username dan password wajib diisi.');
            redirect('/login');
        }

        $user = User::findByUsername($username);

        if (!$user || !password_verify($password, $user['password'])) {
            flash_set('error', 'Kredensial tidak valid.');
            redirect('/login');
        }

        if (empty($user['is_active'])) {
            flash_set('error', 'Akun Anda sedang dinonaktifkan. Silakan hubungi admin.');
            redirect('/login');
        }

        auth_login($user);
        flash_set('success', 'Selamat datang kembali di Portal INIX!');

        $intended = $_SESSION['intended_url'] ?? '/dashboard';
        unset($_SESSION['intended_url']);
        redirect($intended);
    }

    public function logout(): void {
        auth_logout();
        redirect('/login');
    }
}
