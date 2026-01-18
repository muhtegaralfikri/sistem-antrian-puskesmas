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

        $pager = null;
        if ($selectedPoliId) {
            $poli = $this->poliModel->find($selectedPoliId);
            $antrians = $this->antrianModel
                ->select('antrian.*, poli.nama as poli_nama, poli.prefix')
                ->join('poli', 'poli.id = antrian.poli_id')
                ->where('antrian.poli_id', $selectedPoliId)
                // ->where('DATE(antrian.created_at)', date('Y-m-d')) -- Remove date filter filter for debugging
                ->orderBy('antrian.id', 'ASC')
                ->paginate(10);
            
            $pager = $this->antrianModel->pager;
        }

        if ($this->request->isAJAX() || $this->request->getGet('json')) {
            return $this->response->setJSON([
                'success' => true,
                'data' => $antrians,
                'pager' => $pager ? [
                    'current_page' => $pager->getCurrentPage(),
                    'total_pages' => $pager->getPageCount(),
                    'total_items' => $pager->getTotal(),
                    'per_page' => $pager->getPerPage(),
                ] : null,
            ]);
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
