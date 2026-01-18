<?php

declare(strict_types=1);

namespace App\Controllers\Web;

use App\Controllers\BaseController;
use App\Models\SettingsModel;

class AdminSettingsController extends BaseController
{
    protected SettingsModel $settingsModel;

    public function __construct()
    {
        $this->settingsModel = new SettingsModel();
    }

    public function index()
    {
        // Get current settings
        $settings = $this->settingsModel->getAllAsArray();

        // Get polis for reset section
        $poliModel = new \App\Models\PoliModel();
        $polis = $poliModel->getAllPoli();

        $data = [
            'title' => 'Pengaturan Sistem',
            'settings' => [
                'voice_enabled' => $settings['voice_enabled'] ?? '1',
                'voice_volume' => $settings['voice_volume'] ?? '0.8',
                'recall_max' => $settings['recall_max'] ?? '3',
                'display_count' => $settings['display_count'] ?? '5',
                'auto_refresh_interval' => $settings['auto_refresh_interval'] ?? '5',
                'kiosk_show_name' => $settings['kiosk_show_name'] ?? '0',
                'reset_time' => $settings['reset_time'] ?? '00:00',
            ],
            'polis' => $polis,
        ];

        return view('admin/settings', $data);
    }

    public function update()
    {
        if ($this->request->getMethod() !== 'POST') {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid method']);
        }

        // Get all settings from form
        $settings = [
            'voice_enabled' => $this->request->getPost('voice_enabled'),
            'voice_volume' => $this->request->getPost('voice_volume'),
            'recall_max' => $this->request->getPost('recall_max'),
            'display_count' => $this->request->getPost('display_count'),
            'auto_refresh_interval' => $this->request->getPost('auto_refresh_interval'),
            'kiosk_show_name' => $this->request->getPost('kiosk_show_name'),
            'reset_time' => $this->request->getPost('reset_time'),
        ];

        // Validate
        $rules = [
            'voice_volume' => 'required|greater_than_equal_to[0]|less_than_equal_to[1]',
            'recall_max' => 'required|integer|greater_than[0]|less_than[10]',
            'display_count' => 'required|integer|greater_than[0]',
            'auto_refresh_interval' => 'required|integer|greater_than[1]',
            'reset_time' => 'required|regex_match[/^([0-1]?[0-9]|2[0-3]):[0-5][0-9]$/]',
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $this->validator->getErrors()
            ]);
        }

        // Save all settings
        $this->settingsModel->setMultiple($settings);
        $this->settingsModel->clearCache();

        return $this->response->setJSON(['success' => true, 'message' => 'Pengaturan berhasil disimpan']);
    }

    public function resetAntrian($poliId)
    {
        $antrianModel = new \App\Models\AntrianModel();
        
        if ($antrianModel->resetPoli($poliId)) {
            return $this->response->setJSON(['success' => true, 'message' => 'Antrian berhasil direset']);
        }

        return $this->response->setJSON(['success' => false, 'message' => 'Gagal mereset antrian']);
    }

    public function resetAllAntrian()
    {
        $poliModel = new \App\Models\PoliModel();
        $antrianModel = new \App\Models\AntrianModel();
        $polis = $poliModel->getAllPoli();

        $success = true;
        foreach ($polis as $poli) {
            if (!$antrianModel->resetPoli($poli['id'])) {
                $success = false;
            }
        }

        if ($success) {
            return $this->response->setJSON(['success' => true, 'message' => 'Semua antrian berhasil direset']);
        }

        return $this->response->setJSON(['success' => false, 'message' => 'Gagal mereset semua antrian']);
    }
}
