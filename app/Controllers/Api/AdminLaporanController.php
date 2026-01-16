<?php

declare(strict_types=1);

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\AntrianModel;
use App\Models\PoliModel;

class AdminLaporanController extends BaseController
{
    protected AntrianModel $antrianModel;
    protected PoliModel $poliModel;

    public function __construct()
    {
        $this->antrianModel = new AntrianModel();
        $this->poliModel = new PoliModel();
    }

    /**
     * Get daily report
     * GET /api/v1/admin/laporan/harian
     */
    public function harian()
    {
        $tanggal = $this->request->getGet('tanggal') ?? date('Y-m-d');
        $poliId = $this->request->getGet('poli_id');

        $builder = $this->antrianModel->select('
                COUNT(*) as total,
                SUM(CASE WHEN status = "completed" THEN 1 ELSE 0 END) as completed,
                SUM(CASE WHEN status = "skipped" THEN 1 ELSE 0 END) as skipped,
                SUM(CASE WHEN status = "waiting" THEN 1 ELSE 0 END) as waiting,
                poli_id,
                poli.nama as poli_nama,
                poli.prefix
            ')
            ->join('poli', 'poli.id = antrian.poli_id')
            ->where('DATE(antrian.created_at)', $tanggal)
            ->groupBy('poli_id, poli.nama, poli.prefix');

        if ($poliId) {
            $builder->where('poli_id', $poliId);
        }

        $results = $builder->findAll();

        return $this->response->setJSON([
            'success' => true,
            'data' => [
                'tanggal' => $tanggal,
                'reports' => $results,
                'total' => array_sum(array_column($results, 'total')),
                'total_completed' => array_sum(array_column($results, 'completed')),
                'total_skipped' => array_sum(array_column($results, 'skipped')),
            ],
        ]);
    }

    /**
     * Get monthly report
     * GET /api/v1/admin/laporan/bulanan
     */
    public function bulanan()
    {
        $bulan = $this->request->getGet('bulan') ?? date('Y-m');
        $poliId = $this->request->getGet('poli_id');

        $builder = $this->antrianModel->select('
                DATE(created_at) as tanggal,
                COUNT(*) as total,
                SUM(CASE WHEN status = "completed" THEN 1 ELSE 0 END) as completed,
                SUM(CASE WHEN status = "skipped" THEN 1 ELSE 0 END) as skipped,
                poli_id
            ')
            ->where("DATE_FORMAT(created_at, '%Y-%m')", $bulan)
            ->groupBy('DATE(created_at), poli_id')
            ->orderBy('DATE(created_at)', 'ASC');

        if ($poliId) {
            $builder->where('poli_id', $poliId);
        }

        $results = $builder->findAll();

        // Get poli list for labels
        $polis = $this->poliModel->getActivePoli();

        // Group by date
        $byDate = [];
        foreach ($results as $row) {
            $date = date('Y-m-d', strtotime($row['tanggal']));
            if (!isset($byDate[$date])) {
                $byDate[$date] = [
                    'tanggal' => $date,
                    'total' => 0,
                    'completed' => 0,
                    'skipped' => 0,
                    'by_poli' => [],
                ];
            }
            $byDate[$date]['total'] += $row['total'];
            $byDate[$date]['completed'] += $row['completed'];
            $byDate[$date]['skipped'] += $row['skipped'];
            $byDate[$date]['by_poli'][] = $row;
        }

        return $this->response->setJSON([
            'success' => true,
            'data' => [
                'bulan' => $bulan,
                'polis' => $polis,
                'daily' => array_values($byDate),
                'total' => array_sum(array_column($results, 'total')),
                'total_completed' => array_sum(array_column($results, 'completed')),
                'total_skipped' => array_sum(array_column($results, 'skipped')),
            ],
        ]);
    }

    /**
     * Export report
     * GET /api/v1/admin/laporan/export/{type}
     */
    public function export($type)
    {
        // Simple CSV export for now
        if ($type !== 'csv') {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'Only CSV export is supported',
            ]);
        }

        $tanggal = $this->request->getGet('tanggal') ?? date('Y-m-d');

        $results = $this->antrianModel->select('
                antrian.*,
                poli.nama as poli_nama
            ')
            ->join('poli', 'poli.id = antrian.poli_id')
            ->where('DATE(antrian.created_at)', $tanggal)
            ->findAll();

        $csv = "Nomor,Poli,Status,Waktu Ambil,Waktu Panggil,Waktu Selesai\n";
        foreach ($results as $row) {
            $csv .= "{$row['nomor']},{$row['poli_nama']},{$row['status']},{$row['waktu_ambil']},{$row['waktu_panggil']},{$row['waktu_selesai']}\n";
        }

        return $this->response
            ->setHeader('Content-Type', 'text/csv')
            ->setHeader('Content-Disposition', 'attachment; filename="laporan-' . $tanggal . '.csv"')
            ->setBody($csv);
    }
}
