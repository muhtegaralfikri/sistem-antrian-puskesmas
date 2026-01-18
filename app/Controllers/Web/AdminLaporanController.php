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

    public function export($type)
    {
        if ($type === 'harian') {
            $this->exportHarianExcel();
        } elseif ($type === 'bulanan') {
            $this->exportBulananExcel();
        } else {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
    }

    private function exportHarianExcel()
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

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Laporan Harian');

        // Headers
        $headers = ['No', 'Nomor Antrian', 'Poli', 'Waktu Ambil', 'Waktu Panggil', 'Waktu Selesai', 'Status', 'Durasi Tunggu', 'Durasi Layanan'];
        $sheet->fromArray($headers, NULL, 'A1');
        
        // Header Style
        $headerStyle = [
            'font' => ['bold' => true],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['argb' => 'FFE0E0E0'],
            ],
            'borders' => [
                'allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
            ],
        ];
        $sheet->getStyle('A1:I1')->applyFromArray($headerStyle);

        // Data
        $row = 2;
        foreach ($antrian as $i => $a) {
            $waktuAmbil = $a['waktu_ambil'] ? date('H:i:s', strtotime($a['waktu_ambil'])) : '-';
            $waktuPanggil = $a['waktu_panggil'] ? date('H:i:s', strtotime($a['waktu_panggil'])) : '-';
            $waktuSelesai = $a['waktu_selesai'] ? date('H:i:s', strtotime($a['waktu_selesai'])) : '-';
            
            // Calc durations
            $durasiTunggu = '-';
            if ($a['waktu_ambil'] && $a['waktu_panggil']) {
                $wait = strtotime($a['waktu_panggil']) - strtotime($a['waktu_ambil']);
                $durasiTunggu = gmdate('H:i:s', $wait);
            }

            $durasiLayan = '-';
            if ($a['waktu_panggil'] && $a['waktu_selesai']) {
                $serve = strtotime($a['waktu_selesai']) - strtotime($a['waktu_panggil']);
                $durasiLayan = gmdate('H:i:s', $serve);
            }

            $sheet->setCellValue('A' . $row, $i + 1);
            $sheet->setCellValue('B' . $row, $a['nomor']);
            $sheet->setCellValue('C' . $row, $a['poli_nama']);
            $sheet->setCellValue('D' . $row, $waktuAmbil);
            $sheet->setCellValue('E' . $row, $waktuPanggil);
            $sheet->setCellValue('F' . $row, $waktuSelesai);
            $sheet->setCellValue('G' . $row, $a['status']);
            $sheet->setCellValue('H' . $row, $durasiTunggu);
            $sheet->setCellValue('I' . $row, $durasiLayan);
            $row++;
        }

        // Auto size columns
        foreach (range('A', 'I') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Borders for data
        $lastRow = $row - 1;
        $sheet->getStyle('A1:I' . $lastRow)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

        $filename = "laporan-harian-{$tanggal}.xlsx";
        
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    private function exportBulananExcel()
    {
        $bulan = $this->request->getGet('bulan') ?? date('Y-m');
        $poliId = $this->request->getGet('poli_id') ?? '';

        [$tahun, $bulanNum] = explode('-', $bulan);

        $builder = $this->antrianModel->select('antrian.*, poli.nama as poli_nama, poli.prefix')
            ->join('poli', 'poli.id = antrian.poli_id')
            ->where('YEAR(antrian.created_at)', $tahun)
            ->where('MONTH(antrian.created_at)', $bulanNum);

        if ($poliId) {
            $builder->where('antrian.poli_id', $poliId);
        }

        $antrian = $builder->orderBy('antrian.created_at', 'ASC')->findAll();

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Laporan Bulanan');

        // Headers
        $headers = ['No', 'Tanggal', 'Nomor Antrian', 'Poli', 'Waktu Ambil', 'Waktu Panggil', 'Waktu Selesai', 'Status'];
        $sheet->fromArray($headers, NULL, 'A1');

        // Header Style
        $headerStyle = [
            'font' => ['bold' => true],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['argb' => 'FFE0E0E0'],
            ],
            'borders' => [
                'allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
            ],
        ];
        $sheet->getStyle('A1:H1')->applyFromArray($headerStyle);

        $row = 2;
        foreach ($antrian as $i => $a) {
            $tanggal = date('d-m-Y', strtotime($a['created_at']));
            $waktuAmbil = $a['waktu_ambil'] ? date('H:i:s', strtotime($a['waktu_ambil'])) : '-';
            $waktuPanggil = $a['waktu_panggil'] ? date('H:i:s', strtotime($a['waktu_panggil'])) : '-';
            $waktuSelesai = $a['waktu_selesai'] ? date('H:i:s', strtotime($a['waktu_selesai'])) : '-';

            $sheet->setCellValue('A' . $row, $i + 1);
            $sheet->setCellValue('B' . $row, $tanggal);
            $sheet->setCellValue('C' . $row, $a['nomor']);
            $sheet->setCellValue('D' . $row, $a['poli_nama']);
            $sheet->setCellValue('E' . $row, $waktuAmbil);
            $sheet->setCellValue('F' . $row, $waktuPanggil);
            $sheet->setCellValue('G' . $row, $waktuSelesai);
            $sheet->setCellValue('H' . $row, $a['status']);
            $row++;
        }

        // Auto size columns
        foreach (range('A', 'H') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Borders
        $lastRow = $row - 1;
        $sheet->getStyle('A1:H' . $lastRow)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

        $filename = "laporan-bulanan-{$bulan}.xlsx";
        
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }
}
