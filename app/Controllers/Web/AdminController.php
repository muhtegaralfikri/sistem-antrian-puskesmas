<?php

declare(strict_types=1);

namespace App\Controllers\Web;

use App\Controllers\BaseController;
use App\Models\PoliModel;
use App\Models\UserModel;
use App\Models\AntrianModel;
use App\Models\SettingsModel;

class AdminController extends BaseController
{
    protected PoliModel $poliModel;
    protected UserModel $userModel;
    protected AntrianModel $antrianModel;
    protected SettingsModel $settingsModel;

    public function __construct()
    {
        $this->poliModel = new PoliModel();
        $this->userModel = new UserModel();
        $this->antrianModel = new AntrianModel();
        $this->settingsModel = new SettingsModel();
    }

    /**
     * Admin home
     */
    public function index()
    {
        // Get quick stats
        $polis = $this->poliModel->getActivePoli();
        $users = $this->userModel->findAll();

        $todayStats = $this->antrianModel->getTodayStats();

        $data = [
            'polis' => $polis,
            'users' => $users,
            'today_stats' => $todayStats,
        ];

        return view('admin/index', $data);
    }
}

/**
 * Poli Management Controller
 */
class AdminPoliController extends BaseController
{
    protected PoliModel $poliModel;

    public function __construct()
    {
        $this->poliModel = new PoliModel();
    }

    public function index()
    {
        $polis = $this->poliModel->orderBy('urutan', 'ASC')->findAll();

        $data = [
            'polis' => $polis,
        ];

        return view('admin/poli', $data);
    }

    public function create()
    {
        $rules = [
            'nama' => 'required|min_length[3]|max_length[100]',
            'kode' => 'required|min_length[2]|max_length[10]|is_unique[poli.kode]',
            'prefix' => 'required|min_length[1]|max_length[10]|is_unique[poli.prefix]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'nama' => $this->request->getPost('nama'),
            'kode' => strtoupper($this->request->getPost('kode')),
            'prefix' => strtoupper($this->request->getPost('prefix')),
            'aktif' => 1,
            'urutan' => $this->request->getPost('urutan') ?? 0,
        ];

        $this->poliModel->insert($data);

        return redirect()->back()->with('success', 'Poli berhasil ditambahkan');
    }

    public function update($id)
    {
        $rules = [
            'nama' => 'required|min_length[3]|max_length[100]',
            'kode' => "required|min_length[2]|max_length[10]|is_unique[poli.kode,id,{$id}]",
            'prefix' => "required|min_length[1]|max_length[10]|is_unique[poli.prefix,id,{$id}]",
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'nama' => $this->request->getPost('nama'),
            'kode' => strtoupper($this->request->getPost('kode')),
            'prefix' => strtoupper($this->request->getPost('prefix')),
            'aktif' => $this->request->getPost('aktif') ?? 0,
            'urutan' => $this->request->getPost('urutan') ?? 0,
        ];

        $this->poliModel->update($id, $data);

        return redirect()->back()->with('success', 'Poli berhasil diupdate');
    }

    public function delete($id)
    {
        $this->poliModel->delete($id);
        return redirect()->back()->with('success', 'Poli berhasil dihapus');
    }
}

/**
 * User Management Controller
 */
class AdminUsersController extends BaseController
{
    protected UserModel $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function index()
    {
        $users = $this->userModel->orderBy('nama_lengkap', 'ASC')->findAll();
        $polis = new \App\Models\PoliModel();
        $allPoli = $polis->getActivePoli();

        $data = [
            'users' => $users,
            'polis' => $allPoli,
        ];

        return view('admin/users', $data);
    }

    public function create()
    {
        $rules = [
            'username' => 'required|min_length[3]|max_length[50]|is_unique[users.username]',
            'password' => 'required|min_length[6]',
            'nama_lengkap' => 'required|min_length[3]|max_length[100]',
            'role' => 'required|in_list[admin,petugas]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'username' => $this->request->getPost('username'),
            'password' => $this->request->getPost('password'),
            'nama_lengkap' => $this->request->getPost('nama_lengkap'),
            'email' => $this->request->getPost('email'),
            'role' => $this->request->getPost('role'),
            'aktif' => 1,
        ];

        $poliIds = $this->request->getPost('poli_ids');
        if (is_array($poliIds)) {
            $this->userModel->createUserWithPoli($data, $poliIds);
        }

        return redirect()->back()->with('success', 'User berhasil ditambahkan');
    }

    public function update($id)
    {
        $rules = [
            'username' => "required|min_length[3]|max_length[50]|is_unique[users.username,id,{$id}]",
            'nama_lengkap' => 'required|min_length[3]|max_length[100]',
            'role' => 'required|in_list[admin,petugas]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'username' => $this->request->getPost('username'),
            'nama_lengkap' => $this->request->getPost('nama_lengkap'),
            'email' => $this->request->getPost('email'),
            'role' => $this->request->getPost('role'),
            'aktif' => $this->request->getPost('aktif') ?? 0,
        ];

        $password = $this->request->getPost('password');
        if (!empty($password)) {
            $data['password'] = $password;
        }

        $poliIds = $this->request->getPost('poli_ids');
        if (is_array($poliIds)) {
            $this->userModel->updateUserWithPoli($id, $data, $poliIds);
        } else {
            $this->userModel->update($id, $data);
        }

        return redirect()->back()->with('success', 'User berhasil diupdate');
    }

    public function delete($id)
    {
        // Prevent deleting self
        $session = session();
        $currentUserId = $session->get('user_id');
        if ($id == $currentUserId) {
            return redirect()->back()->with('error', 'Tidak dapat menghapus akun sendiri');
        }

        $this->userModel->deleteUserWithPoli($id);
        return redirect()->back()->with('success', 'User berhasil dihapus');
    }
}

/**
 * Reports Controller
 */
class AdminLaporanController extends BaseController
{
    protected AntrianModel $antrianModel;

    public function __construct()
    {
        $this->antrianModel = new AntrianModel();
    }

    public function harian()
    {
        $tanggal = $this->request->getGet('tanggal') ?? date('Y-m-d');

        $poliModel = new \App\Models\PoliModel();
        $polis = $poliModel->getActivePoli();

        $reports = [];
        foreach ($polis as $poli) {
            $stats = $this->antrianModel->getTodayStats($poli['id']);
            $reports[] = [
                'poli' => $poli,
                'stats' => $stats,
            ];
        }

        $data = [
            'tanggal' => $tanggal,
            'reports' => $reports,
        ];

        return view('admin/laporan_harian', $data);
    }

    public function bulanan()
    {
        $bulan = $this->request->getGet('bulan') ?? date('Y-m');

        $data = [
            'bulan' => $bulan,
        ];

        return view('admin/laporan_bulanan', $data);
    }

    public function export($type)
    {
        // Export logic here
        // For now just redirect
        return redirect()->back()->with('info', 'Export feature coming soon');
    }
}

/**
 * Settings Controller
 */
class AdminSettingsController extends BaseController
{
    protected SettingsModel $settingsModel;
    protected AntrianModel $antrianModel;

    public function __construct()
    {
        $this->settingsModel = new SettingsModel();
        $this->antrianModel = new AntrianModel();
    }

    public function index()
    {
        $settings = $this->settingsModel->getAllAsArray();

        $poliModel = new \App\Models\PoliModel();
        $polis = $poliModel->getActivePoli();

        $data = [
            'settings' => $settings,
            'polis' => $polis,
        ];

        return view('admin/settings', $data);
    }

    public function update()
    {
        $rawInput = $this->request->getPost();

        $validKeys = [
            'voice_enabled',
            'voice_volume',
            'reset_time',
            'display_count',
            'kiosk_show_name',
            'auto_refresh_interval',
            'recall_max',
        ];

        foreach ($validKeys as $key) {
            if (isset($rawInput[$key])) {
                $this->settingsModel->setSetting($key, $rawInput[$key]);
            }
        }

        $this->settingsModel->clearCache();

        return redirect()->back()->with('success', 'Settings berhasil diupdate');
    }

    public function resetAntrian($poliId)
    {
        $this->antrianModel->resetPoli($poliId);
        return redirect()->back()->with('success', 'Antrian berhasil direset');
    }

    public function resetAllAntrian()
    {
        $poliModel = new \App\Models\PoliModel();
        $polis = $poliModel->getActivePoli();

        foreach ($polis as $poli) {
            $this->antrianModel->resetPoli($poli['id']);
        }

        return redirect()->back()->with('success', 'Semua antrian berhasil direset');
    }
}
