<?php

declare(strict_types=1);

namespace App\Controllers\Web;

use App\Controllers\BaseController;
use App\Models\PoliModel;
use App\Models\AntrianModel;

class AdminAntrianController extends BaseController
{
    protected PoliModel $poliModel;
    protected AntrianModel $antrianModel;

    public function __construct()
    {
        $this->poliModel = new PoliModel();
        $this->antrianModel = new AntrianModel();
    }

    /**
     * Admin antrian management page
     */
    public function index()
    {
        $polis = $this->poliModel->getActivePoli();
        $selectedPoliId = $this->request->getGet('poli_id') ?? ($polis[0]['id'] ?? null);

        $antrians = [];
        $poli = null;

        if ($selectedPoliId) {
            $poli = $this->poliModel->find($selectedPoliId);
            $antrians = $this->antrianModel
                ->select('antrian.*, poli.nama as poli_nama, poli.prefix')
                ->join('poli', 'poli.id = antrian.poli_id')
                ->where('antrian.poli_id', $selectedPoliId)
                ->where('DATE(antrian.created_at)', date('Y-m-d'))
                ->orderBy('antrian.id', 'ASC')
                ->findAll();
        }

        $data = [
            'polis' => $polis,
            'selected_poli_id' => (int) $selectedPoliId,
            'antrians' => $antrians,
            'poli' => $poli,
        ];

        return view('admin/antrian', $data);
    }

    /**
     * Update queue number (admin only)
     */
    public function updateNomor($id)
    {
        $antrian = $this->antrianModel->find($id);
        if (!$antrian) {
            return $this->response->setStatusCode(404)->setJSON([
                'success' => false,
                'message' => 'Antrian tidak ditemukan',
            ]);
        }

        $rules = [
            'nomor' => 'required|min_length[3]|max_length[20]',
        ];

        if (!$this->validate($rules)) {
            return $this->response->setStatusCode(422)->setJSON([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $this->validator->getErrors(),
            ]);
        }

        $newNomor = strtoupper($this->request->getPost('nomor'));

        // Check if new number already exists for this poli today (excluding current antrian)
        $existing = $this->antrianModel
            ->where('poli_id', $antrian['poli_id'])
            ->where('nomor', $newNomor)
            ->where('id !=', $id)
            ->where('DATE(created_at)', date('Y-m-d'))
            ->first();

        if ($existing) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'Nomor antrian sudah ada untuk poli ini hari ini',
            ]);
        }

        // Update nomor
        $this->antrianModel->update($id, [
            'nomor' => $newNomor,
        ]);

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Nomor antrian berhasil diubah',
            'data' => [
                'id' => $id,
                'nomor' => $newNomor,
            ],
        ]);
    }

    /**
     * Delete antrian (admin only)
     */
    public function delete($id)
    {
        $antrian = $this->antrianModel->find($id);
        if (!$antrian) {
            return $this->response->setStatusCode(404)->setJSON([
                'success' => false,
                'message' => 'Antrian tidak ditemukan',
            ]);
        }

        $this->antrianModel->delete($id);

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Antrian berhasil dihapus',
        ]);
    }
}
