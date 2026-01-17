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
        if ($this->request->getMethod() === 'POST') {
            // Get all settings from form
            $settings = [
                'voice_enabled' => $this->request->getPost('voice_enabled') ? '1' : '0',
                'voice_volume' => $this->request->getPost('voice_volume') ?? '0.8',
                'recall_max' => $this->request->getPost('recall_max') ?? '3',
                'display_count' => $this->request->getPost('display_count') ?? '5',
                'auto_refresh_interval' => $this->request->getPost('auto_refresh_interval') ?? '5',
                'kiosk_show_name' => $this->request->getPost('kiosk_show_name') ? '1' : '0',
                'reset_time' => $this->request->getPost('reset_time') ?? '00:00',
            ];

            // Validate
            $rules = [
                'voice_volume' => 'required|decimal_greater_than[0]|decimal_less_than[1]',
                'recall_max' => 'required|integer|greater_than[0]|less_than[10]',
                'display_count' => 'required|integer|greater_than[0]',
                'auto_refresh_interval' => 'required|integer|greater_than[1]',
                'reset_time' => 'required|regex_match[/^([0-1]?[0-9]|2[0-3]):[0-5][0-9]$/]',
            ];

            if (!$this->validate($rules)) {
                return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
            }

            // Save all settings
            $this->settingsModel->setMultiple($settings);
            $this->settingsModel->clearCache();

            return redirect()->to('/admin/settings')->with('success', 'Pengaturan berhasil disimpan');
        }

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

    public function reset()
    {
        if ($this->request->getMethod() === 'POST') {
            // Reset all antrian
            $poliId = $this->request->getPost('poli_id');

            if ($poliId) {
                // Reset specific poli
                $antrianModel = new \App\Models\AntrianModel();
                $antrianModel->resetPoli($poliId);

                return redirect()->to('/admin/settings')->with('success', 'Antrian berhasil direset');
            } else {
                // Reset all polis
                $poliModel = new \App\Models\PoliModel();
                $polis = $poliModel->getAllPoli();
                $antrianModel = new \App\Models\AntrianModel();

                foreach ($polis as $poli) {
                    $antrianModel->resetPoli($poli['id']);
                }

                return redirect()->to('/admin/settings')->with('success', 'Semua antrian berhasil direset');
            }
        }

        $poliModel = new \App\Models\PoliModel();

        $data = [
            'title' => 'Reset Antrian',
            'polis' => $poliModel->getAllPoli(),
        ];

        return view('admin/reset', $data);
    }
}
