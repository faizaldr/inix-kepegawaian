<?php
$currentUser = auth_user();
$currentUrl = $_SERVER['REQUEST_URI'] ?? '/';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle ?? 'PT INIX | Eduparx — Sistem Kepegawaian & Training IT') ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>

<header class="eduparx-navbar">
    <div class="eduparx-container navbar-inner">
        <a href="/dashboard" class="brand-box" data-testid="brand-logo">
            <span class="brand-badge">INIXINDO</span>
            <span>Eduparx</span>
        </a>

        <?php if ($currentUser): ?>
        <nav aria-label="Navigasi Utama">
            <ul class="nav-menu">
                <li><a href="/dashboard" class="nav-link <?= str_starts_with($currentUrl, '/dashboard') ? 'active' : '' ?>">Home</a></li>
                <li><a href="/pegawai" class="nav-link <?= str_starts_with($currentUrl, '/pegawai') ? 'active' : '' ?>">Data Pegawai</a></li>
                <li><a href="/cuti" class="nav-link <?= str_starts_with($currentUrl, '/cuti') ? 'active' : '' ?>">Pengajuan Cuti</a></li>
            </ul>
        </nav>

        <div class="user-nav-box">
            <span class="badge-role" data-testid="user-role"><?= e($currentUser['role']) ?></span>
            <span style="font-weight: 600; font-size: 0.9rem;" data-testid="user-name"><?= e($currentUser['username']) ?></span>
            <a href="/logout" class="btn btn-outline btn-sm" data-testid="btn-logout">Keluar</a>
        </div>
        <?php else: ?>
        <div class="user-nav-box">
            <a href="/login" class="btn btn-primary btn-sm" data-testid="btn-masuk-nav">Masuk</a>
        </div>
        <?php endif; ?>
    </div>
</header>

<main class="main-content">
    <div class="eduparx-container" style="margin-top: 20px;">
        <?php if ($flashSuccess = flash_get('success')): ?>
            <div class="alert alert-success" role="alert" data-testid="success-message">
                <?= e($flashSuccess) ?>
            </div>
        <?php endif; ?>

        <?php if ($flashError = flash_get('error')): ?>
            <div class="alert alert-danger" role="alert" data-testid="form-error">
                <span data-testid="error-message"><?= e($flashError) ?></span>
            </div>
        <?php endif; ?>
    </div>
