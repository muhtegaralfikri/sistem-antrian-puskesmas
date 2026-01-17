<?php

declare(strict_types=1);

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\AntrianModel;
use App\Models\PoliModel;
use App\Models\UserModel;
use App\Libraries\WebSocket\WebSocketHelper;

class AntrianController extends BaseController
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
     * Ambil nomor antrian (public)
     * POST /api/v1/antrian/ambil
     */
    public function ambil()
    {
        $rules = [
            'poli_id' => 'required|integer',
        ];

        if (!$this->validate($rules)) {
            return $this->response->setStatusCode(422)->setJSON([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $this->validator->getErrors(),
            ]);
        }

        $poliId = (int) $this->request->getPost('poli_id');
        $namaPasien = $this->request->getPost('nama_pasien');

        // Validate poli
        $poli = $this->poliModel->find($poliId);
        if (!$poli || !$poli['aktif']) {
            return $this->response->setStatusCode(404)->setJSON([
                'success' => false,
                'message' => 'Poli tidak ditemukan atau tidak aktif',
            ]);
        }

        // Create antrian
        $antrian = $this->antrianModel->createAntrian($poliId, $namaPasien);

        // Broadcast via WebSocket
        WebSocketHelper::antrianBaru($antrian['poli_id'], $antrian['nomor'], $antrian['id']);

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Nomor antrian berhasil diambil',
            'data' => $antrian,
        ]);
    }

    /**
     * Get tiket detail
     * GET /api/v1/antrian/tiket/{id}
     */
    public function tiket($id)
    {
        $antrian = $this->antrianModel->getWithPoli($id);

        if (!$antrian) {
            return $this->response->setStatusCode(404)->setJSON([
                'success' => false,
                'message' => 'Antrian not found',
            ]);
        }

        return $this->response->setJSON([
            'success' => true,
            'data' => $antrian,
        ]);
    }

    /**
     * Get queue list for a poli (public)
     * GET /api/v1/antrian/queue/{poli_id}
     */
    public function queue($poliId)
    {
        $poli = $this->poliModel->find($poliId);
        if (!$poli) {
            return $this->response->setStatusCode(404)->setJSON([
                'success' => false,
                'message' => 'Poli not found',
            ]);
        }

        $current = $this->antrianModel->getCurrentServing($poliId);
        $waiting = $this->antrianModel->getWaitingQueue($poliId);
        $recent = $this->antrianModel->getRecentForDisplay($poliId, 5);

        return $this->response->setJSON([
            'success' => true,
            'data' => [
                'poli' => $poli,
                'current' => $current,
                'waiting' => $waiting,
                'recent' => $recent,
                'waiting_count' => count($waiting),
            ],
        ]);
    }

    /**
     * Panggil antrian berikutnya (auth required)
     * POST /api/v1/antrian/panggil
     */
    public function panggil()
    {
        $session = session();
        $userId = $session->get('user_id');

        if (!$userId) {
            return $this->response->setStatusCode(401)->setJSON([
                'success' => false,
                'message' => 'Unauthorized',
            ]);
        }

        $rules = [
            'poli_id' => 'required|integer',
        ];

        if (!$this->validate($rules)) {
            return $this->response->setStatusCode(422)->setJSON([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $this->validator->getErrors(),
            ]);
        }

        $poliId = (int) $this->request->getPost('poli_id');

        // Check if user has access to this poli
        if (!$this->userModel->hasPoliAccess($userId, $poliId)) {
            return $this->response->setStatusCode(403)->setJSON([
                'success' => false,
                'message' => 'Anda tidak memiliki akses ke poli ini',
            ]);
        }

        // Get poli info
        $poli = $this->poliModel->find($poliId);
        if (!$poli) {
            return $this->response->setStatusCode(404)->setJSON([
                'success' => false,
                'message' => 'Poli not found',
            ]);
        }

        // Call next antrian
        $antrian = $this->antrianModel->callNext($poliId, $userId);

        if (!$antrian) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Tidak ada antrian yang menunggu',
                'data' => null,
            ]);
        }

        // Broadcast via WebSocket
        WebSocketHelper::antrianPanggil(
            $antrian['poli_id'],
            $antrian['nomor'],
            $antrian['id'],
            $poli
        );

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Antrian berhasil dipanggil',
            'data' => $antrian,
        ]);
    }

    /**
     * Recall/panggil ulang antrian (auth required)
     * POST /api/v1/antrian/recall/{id}
     */
    public function recall($id)
    {
        $session = session();
        $userId = $session->get('user_id');

        if (!$userId) {
            return $this->response->setStatusCode(401)->setJSON([
                'success' => false,
                'message' => 'Unauthorized',
            ]);
        }

        $antrian = $this->antrianModel->find($id);
        if (!$antrian) {
            return $this->response->setStatusCode(404)->setJSON([
                'success' => false,
                'message' => 'Antrian not found',
            ]);
        }

        // Check access
        if (!$this->userModel->hasPoliAccess($userId, $antrian['poli_id'])) {
            return $this->response->setStatusCode(403)->setJSON([
                'success' => false,
                'message' => 'Anda tidak memiliki akses ke poli ini',
            ]);
        }

        // Recall
        $antrian = $this->antrianModel->recall($antrian['poli_id']);

        if (!$antrian) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Tidak ada antrian yang sedang dilayani',
            ]);
        }

        // Get poli info
        $poli = $this->poliModel->find($antrian['poli_id']);

        // Broadcast
        WebSocketHelper::antrianPanggil(
            $antrian['poli_id'],
            $antrian['nomor'],
            $antrian['id'],
            $poli
        );

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Antrian berhasil dipanggil ulang',
            'data' => $antrian,
        ]);
    }

    /**
     * Selesaikan antrian (auth required)
     * POST /api/v1/antrian/selesai/{id}
     */
    public function selesai($id)
    {
        try {
            $session = session();
            $userId = $session->get('user_id');

            if (!$userId) {
                return $this->response->setStatusCode(401)->setJSON([
                    'success' => false,
                    'message' => 'Unauthorized',
                ]);
            }

            $antrian = $this->antrianModel->find($id);
            if (!$antrian) {
                return $this->response->setStatusCode(404)->setJSON([
                    'success' => false,
                    'message' => 'Antrian not found',
                ]);
            }

            // Check access (admin skip check)
            if ($session->get('user_role') !== 'admin') {
                if (!$this->userModel->hasPoliAccess($userId, $antrian['poli_id'])) {
                    return $this->response->setStatusCode(403)->setJSON([
                        'success' => false,
                        'message' => 'Anda tidak memiliki akses ke poli ini',
                    ]);
                }
            }

            // Complete
            $this->antrianModel->complete($id, $userId);

            // Broadcast (suppress errors)
            @WebSocketHelper::antrianSelesai(
                $antrian['poli_id'],
                $antrian['nomor'],
                $antrian['id']
            );

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Antrian berhasil diselesaikan',
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Error in selesai: ' . $e->getMessage());
            return $this->response->setStatusCode(500)->setJSON([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ]);
        }
    }

    /**
     * Skip/lewati antrian (auth required)
     * POST /api/v1/antrian/skip/{id}
     */
    public function skip($id)
    {
        $session = session();
        $userId = $session->get('user_id');

        if (!$userId) {
            return $this->response->setStatusCode(401)->setJSON([
                'success' => false,
                'message' => 'Unauthorized',
            ]);
        }

        $antrian = $this->antrianModel->find($id);
        if (!$antrian) {
            return $this->response->setStatusCode(404)->setJSON([
                'success' => false,
                'message' => 'Antrian not found',
            ]);
        }

        // Check access
        if (!$this->userModel->hasPoliAccess($userId, $antrian['poli_id'])) {
            return $this->response->setStatusCode(403)->setJSON([
                'success' => false,
                'message' => 'Anda tidak memiliki akses ke poli ini',
            ]);
        }

        // Skip
        $this->antrianModel->skip($id);

        // Broadcast
        WebSocketHelper::antrianSkip(
            $antrian['poli_id'],
            $antrian['nomor'],
            $antrian['id']
        );

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Antrian dilewati',
        ]);
    }
}
