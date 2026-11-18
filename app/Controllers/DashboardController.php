<?php

require_once __DIR__ . '/../../includes/helpers.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../Models/Pegawai.php';
require_once __DIR__ . '/../Models/Cuti.php';

class DashboardController {
    public function index(): void {
        require_auth();

        $user = auth_user();
        $totalPegawaiAktif = Pegawai::countAktif();
        $totalCutiPending = Cuti::countPending();

        $pageTitle = 'Dashboard Kepegawaian & Training | Eduparx by Inixindo';

        require_once __DIR__ . '/../Views/dashboard/index.php';
    }
}
