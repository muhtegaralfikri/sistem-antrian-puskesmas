<?php

declare(strict_types=1);

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\PoliModel;
use App\Models\AntrianModel;

class PoliController extends BaseController
{
    protected PoliModel $poliModel;
    protected AntrianModel $antrianModel;

    public function __construct()
    {
        $this->poliModel = new PoliModel();
        $this->antrianModel = new AntrianModel();
    }

    /**
     * Get all active poli
     * GET /api/v1/poli
     */
    public function index()
    {
        $polis = $this->poliModel->getActivePoli();

        // Add queue counts
        foreach ($polis as &$poli) {
            $poli['queue_count'] = $this->antrianModel->getWaitingCount($poli['id']);
            $poli['serving_count'] = $this->antrianModel->getServingCount($poli['id']);
            $poli['completed_count'] = $this->antrianModel->getCompletedCount($poli['id']);
            $poli['current'] = $this->antrianModel->getCurrentServing($poli['id']);
        }

        return $this->response->setJSON([
            'success' => true,
            'data' => $polis,
        ]);
    }

    /**
     * Get single poli by ID
     * GET /api/v1/poli/{id}
     */
    public function show($id)
    {
        $poli = $this->poliModel->find($id);

        if (!$poli) {
            return $this->response->setStatusCode(404)->setJSON([
                'success' => false,
                'message' => 'Poli not found',
            ]);
        }

        // Add queue info
        $poli['queue_count'] = $this->antrianModel->getWaitingCount($poli['id']);
        $poli['serving_count'] = $this->antrianModel->getServingCount($poli['id']);
        $poli['completed_count'] = $this->antrianModel->getCompletedCount($poli['id']);
        $poli['current'] = $this->antrianModel->getCurrentServing($poli['id']);

        return $this->response->setJSON([
            'success' => true,
            'data' => $poli,
        ]);
    }
}
