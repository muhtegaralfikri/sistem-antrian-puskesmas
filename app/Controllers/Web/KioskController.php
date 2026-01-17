<?php

declare(strict_types=1);

namespace App\Controllers\Web;

use App\Controllers\BaseController;
use App\Models\PoliModel;
use App\Models\AntrianModel;
use App\Libraries\WebSocket\WebSocketHelper;

class KioskController extends BaseController
{
    protected PoliModel $poliModel;
    protected AntrianModel $antrianModel;

    public function __construct()
    {
        $this->poliModel = new PoliModel();
        $this->antrianModel = new AntrianModel();
    }

    /**
     * Kiosk home page
     */
    public function index()
    {
        // Auto-reset old queues
        $this->antrianModel->autoResetOldQueues();

        $polis = $this->poliModel->getActivePoli();

        $data = [
            'polis' => $polis,
        ];

        return view('kiosk/index', $data);
    }

    /**
     * Ambil nomor antrian
     */
    public function ambil()
    {
        // Auto-reset old queues
        $this->antrianModel->autoResetOldQueues();

        $rules = [
            'poli_id' => 'required|integer',
        ];

        if (!$this->validate($rules)) {
            if ($this->request->isAJAX()) {
                return $this->response->setStatusCode(422)->setJSON([
                    'success' => false,
                    'message' => 'Poli wajib dipilih',
                ]);
            }

            return redirect()->back()->with('error', 'Poli wajib dipilih');
        }

        $poliId = (int) $this->request->getPost('poli_id');
        $namaPasien = $this->request->getPost('nama_pasien');

        // Validate poli
        $poli = $this->poliModel->find($poliId);
        if (!$poli || !$poli['aktif']) {
            if ($this->request->isAJAX()) {
                return $this->response->setStatusCode(404)->setJSON([
                    'success' => false,
                    'message' => 'Poli tidak ditemukan',
                ]);
            }

            return redirect()->back()->with('error', 'Poli tidak ditemukan');
        }

        // Create antrian
        $antrian = $this->antrianModel->createAntrian($poliId, $namaPasien);

        // Get antrian with poli
        $antrianWithPoli = $this->antrianModel->getWithPoli($antrian['id']);

        // Broadcast via WebSocket
        WebSocketHelper::antrianBaru($antrian['poli_id'], $antrian['nomor'], $antrian['id']);

        if ($this->request->isAJAX()) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Nomor antrian berhasil diambil',
                'data' => $antrianWithPoli,
                'print_url' => "/kiosk/tiket/{$antrian['id']}",
            ]);
        }

        return redirect()->to("/kiosk/tiket/{$antrian['id']}")->with('success', 'Nomor antrian berhasil diambil');
    }

    /**
     * Tiket page (print view)
     */
    public function tiket($id)
    {
        $antrian = $this->antrianModel->getWithPoli((int) $id);

        if (!$antrian) {
            return redirect()->to('/kiosk')->with('error', 'Antrian tidak ditemukan');
        }

        // Get waiting count
        $waitingCount = $this->antrianModel->getWaitingCount($antrian['poli_id']);

        $data = [
            'antrian' => $antrian,
            'waiting_count' => $waitingCount,
        ];

        return view('kiosk/tiket', $data);
    }
}
