<?php

declare(strict_types=1);

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\AntrianModel;
use App\Models\PoliModel;
use App\Models\UserModel;

class DashboardController extends BaseController
{
    protected AntrianModel $antrianModel;
    protected PoliModel $poliModel;
    protected UserModel $userModel;

    public function __construct()
    {
        $this->antrianModel = new AntrianModel();
        $this->poliModel = new PoliModel();
        $this->userModel = new UserModel();
    }

    /**
     * Get dashboard data for logged in user
     * GET /api/v1/dashboard
     */
    public function index()
    {
        $session = session();
        $userId = $session->get('user_id');

        if (!$userId) {
            return $this->response->setStatusCode(401)->setJSON([
                'success' => false,
                'message' => 'Unauthorized',
            ]);
        }

        // Get user info
        $user = $this->userModel->find($userId);
        if (!$user) {
            return $this->response->setStatusCode(404)->setJSON([
                'success' => false,
                'message' => 'User not found',
            ]);
        }

        // Get user's poli
        $userWithPoli = $this->userModel->getUserWithPoli($userId);
        $polis = $userWithPoli['polis'] ?? [];

        // If admin, get all polis
        if ($user['role'] === 'admin') {
            $polis = $this->poliModel->getActivePoli();
        }

        // Build dashboard data
        $poliData = [];
        $totalStats = [
            'total_waiting' => 0,
            'total_serving' => 0,
            'total_completed' => 0,
        ];

        foreach ($polis as $poli) {
            $current = $this->antrianModel->getCurrentServing($poli['id']);
            $waiting = $this->antrianModel->getWaitingQueue($poli['id']);
            $waitingCount = count($waiting);
            $servingCount = $this->antrianModel->getServingCount($poli['id']);
            $completedCount = $this->antrianModel->getCompletedCount($poli['id']);

            $poliData[] = [
                'poli' => $poli,
                'current' => $current,
                'waiting' => $waiting,
                'waiting_count' => $waitingCount,
                'serving_count' => $servingCount,
                'completed_count' => $completedCount,
            ];

            $totalStats['total_waiting'] += $waitingCount;
            $totalStats['total_serving'] += $servingCount;
            $totalStats['total_completed'] += $completedCount;
        }

        return $this->response->setJSON([
            'success' => true,
            'data' => [
                'user' => [
                    'id' => $user['id'],
                    'username' => $user['username'],
                    'nama_lengkap' => $user['nama_lengkap'],
                    'role' => $user['role'],
                ],
                'polis' => $poliData,
                'stats' => $totalStats,
            ],
        ]);
    }

    /**
     * Get queue list for specific poli
     * GET /api/v1/dashboard/queue/{poli_id}
     */
    public function queue($poliId)
    {
        $session = session();
        $userId = $session->get('user_id');

        if (!$userId) {
            return $this->response->setStatusCode(401)->setJSON([
                'success' => false,
                'message' => 'Unauthorized',
            ]);
        }

        // Check access
        if (!$this->userModel->hasPoliAccess($userId, $poliId)) {
            return $this->response->setStatusCode(403)->setJSON([
                'success' => false,
                'message' => 'Anda tidak memiliki akses ke poli ini',
            ]);
        }

        $poli = $this->poliModel->find($poliId);
        if (!$poli) {
            return $this->response->setStatusCode(404)->setJSON([
                'success' => false,
                'message' => 'Poli not found',
            ]);
        }

        $current = $this->antrianModel->getCurrentServing($poliId);
        $waiting = $this->antrianModel->getWaitingQueue($poliId);

        return $this->response->setJSON([
            'success' => true,
            'data' => [
                'poli' => $poli,
                'current' => $current,
                'waiting' => $waiting,
                'waiting_count' => count($waiting),
            ],
        ]);
    }
}
