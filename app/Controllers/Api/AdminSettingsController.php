<?php

declare(strict_types=1);

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\SettingsModel;
use App\Models\AntrianModel;

class AdminSettingsController extends BaseController
{
    protected SettingsModel $settingsModel;
    protected AntrianModel $antrianModel;

    public function __construct()
    {
        $this->settingsModel = new SettingsModel();
        $this->antrianModel = new AntrianModel();
    }

    /**
     * Get all settings
     * GET /api/v1/admin/settings
     */
    public function index()
    {
        $settings = $this->settingsModel->getAllAsArray();

        return $this->response->setJSON([
            'success' => true,
            'data' => $settings,
        ]);
    }

    /**
     * Update settings
     * PUT /api/v1/admin/settings
     */
    public function update()
    {
        $rawInput = $this->request->getRawInput();

        // Validate settings
        $validKeys = [
            'voice_enabled',
            'voice_volume',
            'reset_time',
            'display_count',
            'kiosk_show_name',
            'auto_refresh_interval',
            'recall_max',
        ];

        $updates = [];
        foreach ($rawInput as $key => $value) {
            if (in_array($key, $validKeys)) {
                // Type casting
                if (in_array($key, ['voice_enabled', 'kiosk_show_name'])) {
                    $value = (int) $value;
                } elseif (in_array($key, ['voice_volume'])) {
                    $value = (float) $value;
                } elseif (in_array($key, ['display_count', 'auto_refresh_interval', 'recall_max'])) {
                    $value = (int) $value;
                }
                $updates[$key] = $value;
            }
        }

        if (empty($updates)) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'No valid settings to update',
            ]);
        }

        // Update settings
        foreach ($updates as $key => $value) {
            $this->settingsModel->setSetting($key, $value);
        }

        // Clear cache
        $this->settingsModel->clearCache();

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Settings berhasil diupdate',
            'data' => $this->settingsModel->getAllAsArray(),
        ]);
    }

    /**
     * Reset antrian for specific poli
     * POST /api/v1/admin/reset-antrian/{poli_id}
     */
    public function resetAntrian($poliId)
    {
        $reset = $this->antrianModel->resetPoli($poliId);

        if (!$reset) {
            return $this->response->setStatusCode(500)->setJSON([
                'success' => false,
                'message' => 'Failed to reset antrian',
            ]);
        }

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Antrian berhasil direset untuk poli ini',
        ]);
    }

    /**
     * Reset all antrian
     * POST /api/v1/admin/reset-all
     */
    public function resetAll()
    {
        // Get all active poli
        $poliModel = new \App\Models\PoliModel();
        $polis = $poliModel->getActivePoli();

        $success = true;
        foreach ($polis as $poli) {
            if (!$this->antrianModel->resetPoli($poli['id'])) {
                $success = false;
            }
        }

        if (!$success) {
            return $this->response->setStatusCode(500)->setJSON([
                'success' => false,
                'message' => 'Some antrian failed to reset',
            ]);
        }

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Semua antrian berhasil direset',
        ]);
    }
}
