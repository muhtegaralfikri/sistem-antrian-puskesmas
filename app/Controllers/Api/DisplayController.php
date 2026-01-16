<?php

declare(strict_types=1);

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\AntrianModel;
use App\Models\PoliModel;
use App\Models\SettingsModel;

class DisplayController extends BaseController
{
    protected AntrianModel $antrianModel;
    protected PoliModel $poliModel;
    protected SettingsModel $settingsModel;

    public function __construct()
    {
        $this->antrianModel = new AntrianModel();
        $this->poliModel = new PoliModel();
        $this->settingsModel = new SettingsModel();
    }

    /**
     * Get display data (public)
     * GET /api/v1/display
     */
    public function index()
    {
        // Get all active poli with their antrian data
        $polis = $this->poliModel->getWithQueueCount();
        $displayData = [];

        foreach ($polis as $poli) {
            $current = $this->antrianModel->getCurrentServing($poli['id']);
            $recent = $this->antrianModel->getRecentForDisplay($poli['id'], 3);

            $displayData[] = [
                'poli' => $poli,
                'current' => $current,
                'recent' => $recent,
            ];
        }

        // Get settings
        $settings = [
            'voice_enabled' => $this->settingsModel->isVoiceEnabled(),
            'voice_volume' => $this->settingsModel->getVoiceVolume(),
            'display_count' => $this->settingsModel->getDisplayCount(),
        ];

        return $this->response->setJSON([
            'success' => true,
            'data' => [
                'polis' => $displayData,
                'settings' => $settings,
                'timestamp' => time(),
            ],
        ]);
    }

    /**
     * Get display data for specific poli
     * GET /api/v1/display/{poli_id}
     */
    public function poli($poliId)
    {
        $poli = $this->poliModel->find($poliId);
        if (!$poli) {
            return $this->response->setStatusCode(404)->setJSON([
                'success' => false,
                'message' => 'Poli not found',
            ]);
        }

        $current = $this->antrianModel->getCurrentServing($poliId);
        $recent = $this->antrianModel->getRecentForDisplay($poliId, 5);
        $waitingCount = $this->antrianModel->getWaitingCount($poliId);

        return $this->response->setJSON([
            'success' => true,
            'data' => [
                'poli' => $poli,
                'current' => $current,
                'recent' => $recent,
                'waiting_count' => $waitingCount,
                'timestamp' => time(),
            ],
        ]);
    }
}
