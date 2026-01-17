<?php

declare(strict_types=1);

namespace App\Controllers\Web;

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
     * Display page (TV monitor view)
     */
    public function index()
    {
        // Get all active poli with their antrian data
        $polis = $this->antrianModel->getAllActiveForDisplay();

        // Get display settings
        $displayCount = $this->settingsModel->getDisplayCount();
        $voiceEnabled = $this->settingsModel->isVoiceEnabled();
        $voiceVolume = $this->settingsModel->getVoiceVolume();
        $autoRefreshInterval = $this->settingsModel->getAutoRefreshInterval();

        $data = [
            'polis' => $polis,
            'display_count' => $displayCount,
            'voice_enabled' => $voiceEnabled,
            'voice_volume' => $voiceVolume,
            'auto_refresh_interval' => $autoRefreshInterval,
        ];

        return view('display/index', $data);
    }

    /**
     * Display data endpoint (for AJAX/auto-refresh)
     */
    public function data()
    {
        // Auto-reset old queues (skip waiting from previous days, complete old serving)
        $this->antrianModel->autoResetOldQueues();

        // Get all active poli with their antrian data
        $displayData = $this->antrianModel->getAllActiveForDisplay();

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
     * Single poli display (optional - for specific poli monitor)
     */
    public function poli($poliId)
    {
        $poli = $this->poliModel->find($poliId);
        if (!$poli) {
            return redirect()->to('/display');
        }

        $current = $this->antrianModel->getCurrentServing($poliId);
        $recent = $this->antrianModel->getRecentForDisplay($poliId, 5);

        $data = [
            'poli' => $poli,
            'current' => $current,
            'recent' => $recent,
            'voice_enabled' => $this->settingsModel->isVoiceEnabled(),
            'voice_volume' => $this->settingsModel->getVoiceVolume(),
        ];

        return view('display/poli', $data);
    }
}
