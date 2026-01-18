<?php

declare(strict_types=1);

namespace App\Libraries;

use App\Models\AuditLogModel;
use Config\Database;

/**
 * Backup & Restore Library
 *
 * Menangani backup dan restore database untuk SQLite
 */
class BackupLibrary
{
    private Database $db;
    private AuditLogModel $auditLog;
    private string $backupPath;

    public function __construct()
    {
        $this->db = Database::connect();
        $this->auditLog = new AuditLogModel();
        $this->backupPath = WRITEPATH . 'backups' . DIRECTORY_SEPARATOR;

        // Create backup directory if not exists
        if (!is_dir($this->backupPath)) {
            mkdir($this->backupPath, 0755, true);
        }
    }

    /**
     * Create backup database
     *
     * @param string|null $filename Custom filename
     * @return array Result with status and file info
     */
    public function backup(?string $filename = null): array
    {
        $filename = $filename ?? 'backup-' . date('Y-m-d-H-i-s') . '.db';
        $backupFile = $this->backupPath . $filename;

        try {
            // Get current database file
            $dbFile = $this->db->getDatabase();

            if (!file_exists($dbFile)) {
                return [
                    'success' => false,
                    'message' => 'File database tidak ditemukan',
                ];
            }

            // Copy database file to backup location
            if (!copy($dbFile, $backupFile)) {
                return [
                    'success' => false,
                    'message' => 'Gagal membuat backup',
                ];
            }

            // Get file size
            $fileSize = filesize($backupFile);
            $fileSizeMB = round($fileSize / 1024 / 1024, 2);

            // Log backup action
            $this->auditLog->log(
                AuditLogModel::ACTION_CREATE,
                'Backup',
                null,
                "Database backup: {$filename} ({$fileSizeMB} MB)",
                null,
                ['filename' => $filename, 'size' => $fileSizeMB]
            );

            return [
                'success' => true,
                'message' => 'Backup berhasil dibuat',
                'file' => [
                    'name' => $filename,
                    'path' => $backupFile,
                    'size' => $fileSizeMB,
                    'created_at' => date('Y-m-d H:i:s'),
                ],
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Restore database from backup
     *
     * @param string $filename Backup filename
     * @return array Result with status
     */
    public function restore(string $filename): array
    {
        $backupFile = $this->backupPath . $filename;

        // Validate backup file exists
        if (!file_exists($backupFile)) {
            return [
                'success' => false,
                'message' => 'File backup tidak ditemukan',
            ];
        }

        try {
            // Get current database file
            $dbFile = $this->db->getDatabase();

            // Create emergency backup of current database before restore
            $emergencyBackup = $this->backupPath . 'before-restore-' . date('Y-m-d-H-i-s') . '.db';
            if (!copy($dbFile, $emergencyBackup)) {
                return [
                    'success' => false,
                    'message' => 'Gagal membuat emergency backup',
                ];
            }

            // Close all database connections
            $this->db->close();

            // Replace current database with backup
            if (!copy($backupFile, $dbFile)) {
                return [
                    'success' => false,
                    'message' => 'Gagal restore database',
                ];
            }

            // Reconnect to database
            $this->db->reconnect();

            // Get file size
            $fileSize = filesize($backupFile);
            $fileSizeMB = round($fileSize / 1024 / 1024, 2);

            // Log restore action
            $this->auditLog->log(
                AuditLogModel::ACTION_UPDATE,
                'Restore',
                null,
                "Database restored from: {$filename} ({$fileSizeMB} MB). Emergency backup: " . basename($emergencyBackup),
                null,
                ['filename' => $filename, 'size' => $fileSizeMB, 'emergency_backup' => basename($emergencyBackup)]
            );

            return [
                'success' => true,
                'message' => 'Database berhasil direstore',
                'emergency_backup' => basename($emergencyBackup),
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * List all backup files
     *
     * @return array List of backup files
     */
    public function listBackups(): array
    {
        $files = [];
        $backupFiles = glob($this->backupPath . '*.db');

        foreach ($backupFiles as $file) {
            $filename = basename($file);
            $filesize = round(filesize($file) / 1024 / 1024, 2);
            $filetime = filemtime($file);

            $files[] = [
                'name' => $filename,
                'size' => $filesize,
                'created_at' => date('Y-m-d H:i:s', $filetime),
                'timestamp' => $filetime,
            ];
        }

        // Sort by creation time (newest first)
        usort($files, fn($a, $b) => $b['timestamp'] - $a['timestamp']);

        return $files;
    }

    /**
     * Delete backup file
     *
     * @param string $filename Backup filename
     * @return array Result with status
     */
    public function deleteBackup(string $filename): array
    {
        $backupFile = $this->backupPath . $filename;

        if (!file_exists($backupFile)) {
            return [
                'success' => false,
                'message' => 'File backup tidak ditemukan',
            ];
        }

        try {
            if (!unlink($backupFile)) {
                return [
                    'success' => false,
                    'message' => 'Gagal menghapus file backup',
                ];
            }

            // Log delete action
            $this->auditLog->log(
                AuditLogModel::ACTION_DELETE,
                'Backup',
                null,
                "Backup file deleted: {$filename}",
                null,
                ['filename' => $filename]
            );

            return [
                'success' => true,
                'message' => 'File backup berhasil dihapus',
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Download backup file
     *
     * @param string $filename Backup filename
     * @return void
     */
    public function downloadBackup(string $filename): void
    {
        $backupFile = $this->backupPath . $filename;

        if (!file_exists($backupFile)) {
            header('HTTP/1.1 404 Not Found');
            exit;
        }

        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Content-Length: ' . filesize($backupFile));
        header('Pragma: no-cache');
        header('Expires: 0');

        readfile($backupFile);
        exit;
    }

    /**
     * Clean old backups (keep last N days)
     *
     * @param int $days Number of days to keep
     * @return array Result with status
     */
    public function cleanOldBackups(int $days = 30): array
    {
        $cutoffTime = time() - ($days * 86400);
        $deletedCount = 0;
        $backupFiles = glob($this->backupPath . '*.db');

        foreach ($backupFiles as $file) {
            // Skip emergency backups
            if (strpos(basename($file), 'before-restore-') === 0) {
                continue;
            }

            if (filemtime($file) < $cutoffTime) {
                if (unlink($file)) {
                    $deletedCount++;
                }
            }
        }

        // Log cleanup action
        $this->auditLog->log(
            AuditLogModel::ACTION_DELETE,
            'Backup',
            null,
            "Cleaned old backups (last {$days} days). Deleted {$deletedCount} files.",
            null,
            ['days' => $days, 'deleted_count' => $deletedCount]
        );

        return [
            'success' => true,
            'message' => "Berhasil menghapus {$deletedCount} file backup lama",
            'deleted_count' => $deletedCount,
        ];
    }
}
