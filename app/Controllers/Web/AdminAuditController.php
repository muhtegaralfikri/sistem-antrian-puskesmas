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

        $logs = $this->getFilteredLogs($filters);

        $data = [
            'title' => 'Log Aktivitas',
            'logs' => $logs,
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
     * Get logs with filters
     */
    private function getFilteredLogs(array $filters): array
    {
        $builder = $this->auditLogModel->builder();

        // Filter by action
        if (!empty($filters['action'])) {
            $builder->where('action', $filters['action']);
        }

        // Filter by entity type
        if (!empty($filters['entity_type'])) {
            $builder->where('entity_type', $filters['entity_type']);
        }

        // Filter by user
        if (!empty($filters['user_id'])) {
            $builder->where('user_id', $filters['user_id']);
        }

        // Filter by date range
        if (!empty($filters['start_date'])) {
            $builder->where('created_at >=', $filters['start_date'] . ' 00:00:00');
        }

        if (!empty($filters['end_date'])) {
            $builder->where('created_at <=', $filters['end_date'] . ' 23:59:59');
        }

        return $builder->orderBy('created_at', 'DESC')
            ->limit(200)
            ->get()
            ->getResultArray();
    }

    /**
     * View log details
     */
    public function view(int $id)
    {
        $log = $this->auditLogModel->find($id);

        if (!$log) {
            return redirect()->to('/admin/audit-log')->with('error', 'Log tidak ditemukan');
        }

        $data = [
            'title' => 'Detail Log',
            'log' => $log,
        ];

        return view('admin/audit_log_view', $data);
    }

    /**
     * Export logs to CSV
     */
    public function export()
    {
        $filters = [
            'action' => $this->request->getGet('action'),
            'entity_type' => $this->request->getGet('entity_type'),
            'start_date' => $this->request->getGet('start_date'),
            'end_date' => $this->request->getGet('end_date'),
        ];

        $logs = $this->getFilteredLogs($filters);

        // Set headers for CSV download
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="audit-log-' . date('Y-m-d') . '.csv"');

        $output = fopen('php://output', 'w');

        // Write CSV headers
        fputcsv($output, [
            'ID',
            'Waktu',
            'User',
            'Aksi',
            'Entity',
            'Entity ID',
            'Deskripsi',
            'IP Address',
        ]);

        // Write data
        foreach ($logs as $log) {
            fputcsv($output, [
                $log['id'],
                $log['created_at'],
                $log['username'] ?? 'System',
                $log['action'],
                $log['entity_type'] ?? '-',
                $log['entity_id'] ?? '-',
                $log['description'] ?? '-',
                $log['ip_address'] ?? '-',
            ]);
        }

        fclose($output);
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
