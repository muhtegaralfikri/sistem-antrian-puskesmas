<?php

declare(strict_types=1);

namespace App\Controllers\Web;

use App\Controllers\BaseController;
use App\Libraries\BackupLibrary;

class AdminBackupController extends BaseController
{
    protected BackupLibrary $backup;

    public function __construct()
    {
        $this->backup = new BackupLibrary();
    }

    /**
     * Display backup/restore page
     */
    public function index()
    {
        $backups = $this->backup->listBackups();

        $data = [
            'title' => 'Backup & Restore',
            'backups' => $backups,
            'total_size' => array_sum(array_column($backups, 'size')),
        ];

        return view('admin/backup', $data);
    }

    /**
     * Create new backup
     */
    public function create()
    {
        if (!$this->request->isAJAX()) {
            return redirect()->to('/admin/backup');
        }

        $result = $this->backup->backup();

        return $this->response->setJSON($result);
    }

    /**
     * Restore from backup
     */
    public function restore()
    {
        if (!$this->request->isAJAX()) {
            return redirect()->to('/admin/backup');
        }

        $filename = $this->request->getPost('filename');

        if (empty($filename)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Filename tidak valid',
            ]);
        }

        $result = $this->backup->restore($filename);

        return $this->response->setJSON($result);
    }

    /**
     * Delete backup file
     */
    public function delete()
    {
        if (!$this->request->isAJAX()) {
            return redirect()->to('/admin/backup');
        }

        $filename = $this->request->getPost('filename');

        if (empty($filename)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Filename tidak valid',
            ]);
        }

        $result = $this->backup->deleteBackup($filename);

        return $this->response->setJSON($result);
    }

    /**
     * Download backup file
     */
    public function download()
    {
        $filename = $this->request->GetGet('file');

        if (empty($filename)) {
            return redirect()->to('/admin/backup')->with('error', 'Filename tidak valid');
        }

        $this->backup->downloadBackup($filename);
    }

    /**
     * Clean old backups
     */
    public function clean()
    {
        if (!$this->request->isAJAX()) {
            return redirect()->to('/admin/backup');
        }

        $days = (int) $this->request->getPost('days', 30);

        if ($days < 7) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Minimal retention adalah 7 hari',
            ]);
        }

        $result = $this->backup->cleanOldBackups($days);

        return $this->response->setJSON($result);
    }
}
