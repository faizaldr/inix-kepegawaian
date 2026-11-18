<?php

require_once __DIR__ . '/../../includes/helpers.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../Models/Pegawai.php';

class PegawaiController {
    public function index(): void {
        require_auth();

        $search = trim($_GET['search'] ?? '');
        $status = trim($_GET['status'] ?? '');
        $jabatan = trim($_GET['jabatan'] ?? '');

        $pegawais = Pegawai::getAll($search, $status, $jabatan);
        $currentUser = auth_user();
        $pageTitle = 'Katalog & Data Pegawai | Eduparx PT INIX';

        require_once __DIR__ . '/../Views/pegawai/index.php';
    }

    public function create(): void {
        require_role('hr', 'admin');

        $mode = 'create';
        $pegawai = [
            'id' => '',
            'nip' => '',
            'nama_lengkap' => '',
            'jabatan' => '',
            'status' => 'aktif',
            'kuota_cuti' => 12
        ];
        $pageTitle = 'Tambah Data Pegawai Baru | Eduparx PT INIX';

        require_once __DIR__ . '/../Views/pegawai/form.php';
    }

    public function store(): void {
        require_role('hr', 'admin');
        csrf_verify();

        $nip = trim($_POST['nip'] ?? '');
        $nama_lengkap = trim($_POST['nama_lengkap'] ?? '');
        $jabatan = trim($_POST['jabatan'] ?? '');
        $status = trim($_POST['status'] ?? 'aktif');

        if ($nip === '' || $nama_lengkap === '' || $jabatan === '') {
            flash_set('error', 'Field NIP, Nama Lengkap, dan Jabatan wajib diisi.');
            redirect('/pegawai/tambah');
        }

        if (Pegawai::getByNip($nip)) {
            flash_set('error', "NIP {$nip} sudah terdaftar di sistem.");
            redirect('/pegawai/tambah');
        }

        Pegawai::create([
            'nip' => $nip,
            'nama_lengkap' => $nama_lengkap,
            'jabatan' => $jabatan,
            'status' => in_array($status, ['aktif', 'nonaktif'], true) ? $status : 'aktif'
        ]);

        flash_set('success', 'Data pegawai berhasil ditambahkan.');
        redirect('/pegawai');
    }

    public function edit(): void {
        require_role('hr', 'admin');

        $id = (int) ($_GET['id'] ?? 0);
        $pegawai = Pegawai::getById($id);

        if (!$pegawai) {
            flash_set('error', 'Data pegawai tidak ditemukan.');
            redirect('/pegawai');
        }

        $mode = 'edit';
        $pageTitle = 'Edit Data Pegawai | Eduparx PT INIX';

        require_once __DIR__ . '/../Views/pegawai/form.php';
    }

    public function update(): void {
        require_role('hr', 'admin');
        csrf_verify();

        $id = (int) ($_POST['id'] ?? 0);
        $nip = trim($_POST['nip'] ?? '');
        $nama_lengkap = trim($_POST['nama_lengkap'] ?? '');
        $jabatan = trim($_POST['jabatan'] ?? '');
        $status = trim($_POST['status'] ?? 'aktif');

        if ($id <= 0) {
            flash_set('error', 'ID pegawai tidak valid.');
            redirect('/pegawai');
        }

        if ($nip === '' || $nama_lengkap === '' || $jabatan === '') {
            flash_set('error', 'Field NIP, Nama Lengkap, dan Jabatan wajib diisi.');
            redirect("/pegawai/edit?id={$id}");
        }

        $existing = Pegawai::getByNip($nip);
        if ($existing && (int)$existing['id'] !== $id) {
            flash_set('error', "NIP {$nip} sudah digunakan oleh pegawai lain.");
            redirect("/pegawai/edit?id={$id}");
        }

        Pegawai::update($id, [
            'nip' => $nip,
            'nama_lengkap' => $nama_lengkap,
            'jabatan' => $jabatan,
            'status' => in_array($status, ['aktif', 'nonaktif'], true) ? $status : 'aktif'
        ]);

        flash_set('success', 'Data pegawai berhasil diperbarui.');
        redirect('/pegawai');
    }

    public function delete(): void {
        require_role('admin');
        csrf_verify();

        $id = (int) ($_POST['id'] ?? 0);
        if ($id > 0) {
            Pegawai::delete($id);
            flash_set('success', 'Data pegawai berhasil dihapus.');
        } else {
            flash_set('error', 'ID data pegawai tidak valid.');
        }

        redirect('/pegawai');
    }
}
