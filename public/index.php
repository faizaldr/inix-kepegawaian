<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Error reporting untuk environment development
error_reporting(E_ALL);
ini_set('display_errors', '1');

// Dependensi Core
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/Router.php';

// Models
require_once __DIR__ . '/../app/Models/User.php';
require_once __DIR__ . '/../app/Models/Pegawai.php';
require_once __DIR__ . '/../app/Models/Cuti.php';

// Controllers
require_once __DIR__ . '/../app/Controllers/AuthController.php';
require_once __DIR__ . '/../app/Controllers/DashboardController.php';
require_once __DIR__ . '/../app/Controllers/PegawaiController.php';
require_once __DIR__ . '/../app/Controllers/CutiController.php';

$router = new Router();

// Health Check Endpoint (untuk CI/CD & Docker Healthcheck)
$router->get('/health', function() {
    header('Content-Type: application/json');
    echo json_encode([
        'status' => 'ok',
        'app'    => 'PT INIX Monolith',
        'time'   => date('c')
    ]);
    exit;
});

// Web Routes
$router->get('/', function() {
    redirect('/dashboard');
});

// Autentikasi
$router->get('/login', [AuthController::class, 'showLogin']);
$router->post('/login', [AuthController::class, 'login']);
$router->get('/logout', [AuthController::class, 'logout']);
$router->post('/logout', [AuthController::class, 'logout']);

// Dashboard
$router->get('/dashboard', [DashboardController::class, 'index']);

// Pegawai
$router->get('/pegawai', [PegawaiController::class, 'index']);
$router->get('/pegawai/tambah', [PegawaiController::class, 'create']);
$router->post('/pegawai/simpan', [PegawaiController::class, 'store']);
$router->get('/pegawai/edit', [PegawaiController::class, 'edit']);
$router->post('/pegawai/update', [PegawaiController::class, 'update']);
$router->post('/pegawai/hapus', [PegawaiController::class, 'delete']);

// Cuti
$router->get('/cuti', [CutiController::class, 'index']);
$router->get('/cuti/ajukan', [CutiController::class, 'create']);
$router->post('/cuti/simpan', [CutiController::class, 'store']);
$router->post('/cuti/status', [CutiController::class, 'updateStatus']);

// Dispatch request
$router->dispatch($_SERVER['REQUEST_URI'] ?? '/', $_SERVER['REQUEST_METHOD'] ?? 'GET');
