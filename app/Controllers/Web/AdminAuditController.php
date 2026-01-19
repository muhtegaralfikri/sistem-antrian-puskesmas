<?php

declare(strict_types=1);

namespace App\Controllers\Web;

use App\Controllers\BaseController;
use App\Models\AuditLogModel;

class AdminAuditController extends BaseController
{
    protected AuditLogModel $auditLogModel;

    public function __construct()
    {
        $this->auditLogModel = new AuditLogModel();
    }

    /**
     * Display audit logs page
     */
    public function index()
    {
        $filters = [
            'action' => $this->request->getGet('action'),
            'entity_type' => $this->request->getGet('entity_type'),
            'start_date' => $this->request->getGet('start_date'),
            'end_date' => $this->request->getGet('end_date'),
            'user_id' => $this->request->getGet('user_id'),
        ];

        // Use the model with filters
        $this->applyFilters($filters);
        $logs = $this->auditLogModel->orderBy('created_at', 'DESC')->paginate(10);

        $data = [
            'title' => 'Log Aktivitas',
            'logs' => $logs,
            'pager' => $this->auditLogModel->pager,
            'filters' => $filters,
            'actions' => [
                'LOGIN' => 'Login',
                'LOGOUT' => 'Logout',
                'CREATE' => 'Buat Data',
                'UPDATE' => 'Update Data',
                'DELETE' => 'Hapus Data',
                'CALL' => 'Panggil Antrian',
                'RECALL' => 'Panggil Ulang',
                'COMPLETE' => 'Selesai',
                'SKIP' => 'Lewati',
                'RESET' => 'Reset',
            ],
            'entity_types' => [
                'User' => 'Pengguna',
                'Poli' => 'Poli',
                'Antrian' => 'Antrian',
                'Settings' => 'Pengaturan',
            ],
        ];

        return view('admin/audit_log', $data);
    }

    /**
     * Apply filters to the model
     */
    private function applyFilters(array $filters)
    {
        // Filter by action
        if (!empty($filters['action'])) {
            $this->auditLogModel->where('action', $filters['action']);
        }

        // Filter by entity type
        if (!empty($filters['entity_type'])) {
            $this->auditLogModel->where('entity_type', $filters['entity_type']);
        }

        // Filter by user
        if (!empty($filters['user_id'])) {
            $this->auditLogModel->where('user_id', $filters['user_id']);
        }

        // Filter by date range
        if (!empty($filters['start_date'])) {
            $this->auditLogModel->where('created_at >=', $filters['start_date'] . ' 00:00:00');
        }

        if (!empty($filters['end_date'])) {
            $this->auditLogModel->where('created_at <=', $filters['end_date'] . ' 23:59:59');
        }
    }

    /**
     * Get Builder with filters - REMOVED/REPLACED
     */
    // private function getLogBuilder(array $filters) ... 

    /**
     * Get logs with filters (Deprecated - used by index logic inline now)
     */
    // private function getFilteredLogs(array $filters): array { ... } 
    
    /**
     * Get new logs since last ID (for polling)
     */
    public function updates()
    {
        $lastId = (int) $this->request->getGet('last_id');
        
        // Return emtpy if invalid ID
        if ($lastId <= 0) {
             return $this->response->setJSON(['success' => true, 'logs' => []]);
        }

        // Apply same filters as main page if needed, but for now just get everything new
        // Optimization: limit to 50 to prevent overflow if client was disconnected long
        $logs = $this->auditLogModel->where('id >', $lastId)
            ->orderBy('id', 'ASC') // Get oldest first to append correctly (or client handles sort)
            ->limit(50)
            ->findAll();

        return $this->response->setJSON([
            'success' => true,
            'logs' => $logs
        ]);
    }

    public function export()
    {
        $filters = [
            'action' => $this->request->getGet('action'),
            'entity_type' => $this->request->getGet('entity_type'),
            'start_date' => $this->request->getGet('start_date'),
            'end_date' => $this->request->getGet('end_date'),
            'user_id' => $this->request->getGet('user_id'),
        ];

        // Use the model to get all results
        $this->applyFilters($filters);
        $logs = $this->auditLogModel->orderBy('created_at', 'DESC')->findAll();

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Audit Logs');

        // Headers
        $headers = ['ID', 'Waktu', 'User', 'Aksi', 'Entity', 'Entity ID', 'Deskripsi', 'IP Address'];
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

        // Data
        $row = 2;
        foreach ($logs as $log) {
            $sheet->setCellValue('A' . $row, $log['id']);
            $sheet->setCellValue('B' . $row, $log['created_at']);
            $sheet->setCellValue('C' . $row, $log['username'] ?? 'System');
            $sheet->setCellValue('D' . $row, $log['action']);
            $sheet->setCellValue('E' . $row, $log['entity_type'] ?? '-');
            $sheet->setCellValue('F' . $row, $log['entity_id'] ?? '-');
            $sheet->setCellValue('G' . $row, $log['description'] ?? '-');
            $sheet->setCellValue('H' . $row, $log['ip_address'] ?? '-');
            $row++;
        }

        // Auto size columns
        foreach (range('A', 'H') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Borders for data
        $lastRow = $row - 1;
        $sheet->getStyle('A1:H' . $lastRow)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

        $filename = "audit-log-" . date('Y-m-d') . ".xlsx";
        
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    /**
     * Clean old logs
     */
    public function clean()
    {
        $days = (int) $this->request->getPost('days');
        if ($days < 30) {
            return redirect()->back()->with('error', 'Minimal retention adalah 30 hari');
        }

        $deleted = $this->auditLogModel->cleanOldLogs($days);

        return redirect()->back()->with('success', "Berhasil menghapus {$deleted} log lama");
    }
}
