<?php

require_once __DIR__ . '/../../includes/helpers.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../Models/Cuti.php';
require_once __DIR__ . '/../Models/Pegawai.php';

class CutiController {
    public function index(): void {
        require_auth();

        $currentUser = auth_user();
        $isPegawai = ($currentUser['role'] === 'pegawai');

        $kuotaInfo = null;
        $cutis = [];
        $currentPegawai = null;

        if ($isPegawai) {
            $currentPegawai = Pegawai::getByUserId((int)$currentUser['id']);
            if ($currentPegawai) {
                $cutis = Cuti::getAll((int)$currentPegawai['id']);
                $kuotaInfo = Cuti::getKuotaInfo((int)$currentPegawai['id']);
            } else {
                $cutis = [];
                $kuotaInfo = ['kuota_tahunan' => 12, 'terpakai' => 0, 'pending_count' => 0, 'sisa_kuota' => 12];
            }
        } else {
            $cutis = Cuti::getAll();
        }

        $pageTitle = 'Pengajuan & Riwayat Cuti | Eduparx PT INIX';

        require_once __DIR__ . '/../Views/cuti/index.php';
    }

    public function create(): void {
        require_role('pegawai');

        $currentUser = auth_user();
        $currentPegawai = Pegawai::getByUserId((int)$currentUser['id']);

        if (!$currentPegawai) {
            flash_set('error', 'Profil data pegawai Anda belum terhubung. Hubungi HR.');
            redirect('/cuti');
        }

        $kuotaInfo = Cuti::getKuotaInfo((int)$currentPegawai['id']);
        $pageTitle = 'Ajukan Permohonan Cuti | Eduparx PT INIX';

        require_once __DIR__ . '/../Views/cuti/form.php';
    }

    public function store(): void {
        require_role('pegawai');
        csrf_verify();

        $currentUser = auth_user();
        $currentPegawai = Pegawai::getByUserId((int)$currentUser['id']);

        if (!$currentPegawai) {
            flash_set('error', 'Profil pegawai tidak ditemukan.');
            redirect('/cuti');
        }

        $tgl_mulai = trim($_POST['tgl_mulai'] ?? '');
        $tgl_selesai = trim($_POST['tgl_selesai'] ?? '');
        $alasan = trim($_POST['alasan'] ?? '');

        if ($tgl_mulai === '' || $tgl_selesai === '' || $alasan === '') {
            flash_set('error', 'Semua kolom formulir cuti wajib diisi.');
            redirect('/cuti/ajukan');
        }

        $start = new DateTime($tgl_mulai);
        $end = new DateTime($tgl_selesai);

        if ($end < $start) {
            flash_set('error', 'Tanggal selesai tidak boleh lebih awal dari tanggal mulai.');
            redirect('/cuti/ajukan');
        }

        $durasi = $start->diff($end)->days + 1;
        $kuotaInfo = Cuti::getKuotaInfo((int)$currentPegawai['id']);

        if ($durasi > $kuotaInfo['sisa_kuota']) {
            flash_set('error', "Durasi cuti ({$durasi} hari) melebihi sisa kuota tahunan Anda ({$kuotaInfo['sisa_kuota']} hari)!");
            redirect('/cuti/ajukan');
        }

        Cuti::create([
            'pegawai_id'  => (int)$currentPegawai['id'],
            'tgl_mulai'   => $tgl_mulai,
            'tgl_selesai' => $tgl_selesai,
            'alasan'      => $alasan
        ]);

        flash_set('success', 'Pengajuan cuti berhasil diajukan dan menunggu persetujuan Manajer.');
        redirect('/cuti');
    }

    public function updateStatus(): void {
        require_role('manajer');
        csrf_verify();

        $id = (int) ($_POST['id'] ?? 0);
        $status = trim($_POST['status'] ?? '');

        if ($id <= 0 || !in_array($status, ['APPROVED', 'REJECTED'], true)) {
            flash_set('error', 'Permintaan perubahan status cuti tidak valid.');
            redirect('/cuti');
        }

        Cuti::updateStatus($id, $status);
        flash_set('success', "Status pengajuan cuti berhasil diperbarui menjadi {$status}.");
        redirect('/cuti');
    }
}
