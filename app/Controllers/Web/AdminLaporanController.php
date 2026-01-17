<?php

declare(strict_types=1);

namespace App\Controllers\Web;

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

    public function index()
    {
        return redirect()->to('/admin/laporan/harian');
    }

    public function harian()
    {
        $tanggal = $this->request->getGet('tanggal') ?? date('Y-m-d');
        $poliId = $this->request->getGet('poli_id') ?? '';

        $builder = $this->antrianModel->select('antrian.*, poli.nama as poli_nama, poli.prefix')
            ->join('poli', 'poli.id = antrian.poli_id')
            ->where('DATE(antrian.created_at)', $tanggal);

        if ($poliId) {
            $builder->where('antrian.poli_id', $poliId);
        }

        $antrian = $builder->orderBy('antrian.id', 'ASC')->findAll();

        // Get statistics
        $stats = [
            'total' => count($antrian),
            'waiting' => count(array_filter($antrian, fn($a) => $a['status'] === 'waiting')),
            'serving' => count(array_filter($antrian, fn($a) => in_array($a['status'], ['called', 'serving']))),
            'completed' => count(array_filter($antrian, fn($a) => $a['status'] === 'completed')),
            'skipped' => count(array_filter($antrian, fn($a) => $a['status'] === 'skipped')),
        ];

        $data = [
            'title' => 'Laporan Harian',
            'antrian' => $antrian,
            'stats' => $stats,
            'tanggal' => $tanggal,
            'poli_id' => $poliId,
            'polis' => $this->poliModel->getAllPoli(),
        ];

        return view('admin/laporan_harian', $data);
    }

    public function bulanan()
    {
        $bulan = $this->request->getGet('bulan') ?? date('Y-m');
        $poliId = $this->request->getGet('poli_id') ?? '';

        // Parse bulan
        [$tahun, $bulanNum] = explode('-', $bulan);

        $builder = $this->antrianModel->select('antrian.*, poli.nama as poli_nama, poli.prefix')
            ->join('poli', 'poli.id = antrian.poli_id')
            ->where('YEAR(antrian.created_at)', $tahun)
            ->where('MONTH(antrian.created_at)', $bulanNum);

        if ($poliId) {
            $builder->where('antrian.poli_id', $poliId);
        }

        $antrian = $builder->orderBy('antrian.id', 'ASC')->findAll();

        // Get statistics
        $stats = [
            'total' => count($antrian),
            'waiting' => count(array_filter($antrian, fn($a) => $a['status'] === 'waiting')),
            'serving' => count(array_filter($antrian, fn($a) => in_array($a['status'], ['called', 'serving']))),
            'completed' => count(array_filter($antrian, fn($a) => $a['status'] === 'completed')),
            'skipped' => count(array_filter($antrian, fn($a) => $a['status'] === 'skipped')),
        ];

        // Group by date for chart
        $dailyStats = [];
        foreach ($antrian as $a) {
            $date = date('Y-m-d', strtotime($a['created_at']));
            if (!isset($dailyStats[$date])) {
                $dailyStats[$date] = ['total' => 0, 'completed' => 0];
            }
            $dailyStats[$date]['total']++;
            if ($a['status'] === 'completed') {
                $dailyStats[$date]['completed']++;
            }
        }

        $data = [
            'title' => 'Laporan Bulanan',
            'antrian' => $antrian,
            'stats' => $stats,
            'bulan' => $bulan,
            'poli_id' => $poliId,
            'polis' => $this->poliModel->getAllPoli(),
            'daily_stats' => $dailyStats,
        ];

        return view('admin/laporan_bulanan', $data);
    }

    public function exportHarian()
    {
        $tanggal = $this->request->getGet('tanggal') ?? date('Y-m-d');
        $poliId = $this->request->getGet('poli_id') ?? '';

        $builder = $this->antrianModel->select('antrian.*, poli.nama as poli_nama, poli.prefix')
            ->join('poli', 'poli.id = antrian.poli_id')
            ->where('DATE(antrian.created_at)', $tanggal);

        if ($poliId) {
            $builder->where('antrian.poli_id', $poliId);
        }

        $antrian = $builder->orderBy('antrian.id', 'ASC')->findAll();

        // Generate CSV
        $filename = "laporan-harian-{$tanggal}.csv";
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        $output = fopen('php://output', 'w');

        // Header
        fputcsv($output, ['No', 'Nomor', 'Poli', 'Nama Pasien', 'Waktu Ambil', 'Waktu Panggil', 'Waktu Selesai', 'Status']);

        // Data
        foreach ($antrian as $i => $a) {
            fputcsv($output, [
                $i + 1,
                $a['nomor'],
                $a['poli_nama'],
                $a['nama_pasien'] ?? '-',
                $a['waktu_ambil'] ?? '-',
                $a['waktu_panggil'] ?? '-',
                $a['waktu_selesai'] ?? '-',
                $a['status'],
            ]);
        }

        fclose($output);
        exit;
    }
}
