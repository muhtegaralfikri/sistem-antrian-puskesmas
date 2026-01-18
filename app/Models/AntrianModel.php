<?php

declare(strict_types=1);

namespace App\Models;

use CodeIgniter\Model;

class AntrianModel extends Model
{
    protected $table = 'antrian';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'poli_id',
        'nomor',
        'status',
        'nama_pasien',
        'waktu_ambil',
        'waktu_panggil',
        'waktu_selesai',
        'dipanggil_oleh',
        'selesai_oleh',
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // Status constants
    public const STATUS_WAITING = 'waiting';
    public const STATUS_CALLED = 'called';
    public const STATUS_SERVING = 'serving';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_SKIPPED = 'skipped';

    /**
     * Get waiting count for a poli
     */
    public function getWaitingCount(int $poliId): int
    {
        return $this->where('poli_id', $poliId)
            ->where('status', self::STATUS_WAITING)
            ->countAllResults();
    }

    /**
     * Get serving count for a poli
     */
    public function getServingCount(int $poliId): int
    {
        return $this->where('poli_id', $poliId)
            ->whereIn('status', [self::STATUS_CALLED, self::STATUS_SERVING])
            ->countAllResults();
    }

    /**
     * Get completed count for a poli (today)
     */
    public function getCompletedCount(int $poliId): int
    {
        $today = date('Y-m-d');
        return $this->where('poli_id', $poliId)
            ->where('status', self::STATUS_COMPLETED)
            ->where('waktu_selesai >=', $today . ' 00:00:00')
            ->where('waktu_selesai <=', $today . ' 23:59:59')
            ->countAllResults();
    }

    /**
     * Get current serving antrian for a poli
     */
    public function getCurrentServing(int $poliId): ?array
    {
        return $this->where('poli_id', $poliId)
            ->whereIn('status', [self::STATUS_CALLED, self::STATUS_SERVING])
            ->orderBy('id', 'DESC')
            ->first();
    }

    /**
     * Get all waiting antrian for a poli
     */
    public function getWaitingQueue(int $poliId): array
    {
        return $this->where('poli_id', $poliId)
            ->where('status', self::STATUS_WAITING)
            ->orderBy('id', 'ASC')
            ->findAll();
    }

    /**
     * Get recent called/completed antrian for display
     */
    public function getRecentForDisplay(int $poliId, int $limit = 5): array
    {
        return $this->select('antrian.*, poli.nama as poli_nama, poli.prefix')
            ->join('poli', 'poli.id = antrian.poli_id')
            ->where('antrian.poli_id', $poliId)
            ->whereIn('antrian.status', [self::STATUS_CALLED, self::STATUS_SERVING, self::STATUS_COMPLETED])
            ->orderBy('antrian.waktu_panggil', 'DESC')
            ->limit($limit)
            ->findAll();
    }

    /**
     * Get all active antrian for all poli (for display page)
     */
    public function getAllActiveForDisplay(): array
    {
        $poliModel = new PoliModel();
        $polis = $poliModel->getActivePoli();
        $result = [];

        foreach ($polis as $poli) {
            $current = $this->getCurrentServing($poli['id']);
            $recent = $this->getRecentForDisplay($poli['id'], 3);
            $waitingCount = $this->getWaitingCount($poli['id']);

            $result[] = [
                'poli' => $poli,
                'current' => $current,
                'recent' => $recent,
                'waiting_count' => $waitingCount,
            ];
        }

        return $result;
    }

    /**
     * Generate new antrian number
     */
    public function generateNomor(int $poliId): string
    {
        $poliModel = new PoliModel();
        $poli = $poliModel->find($poliId);

        if (!$poli) {
            throw new \Exception('Poli not found');
        }

        // Get last antrian for today
        $today = date('Y-m-d');
        $lastAntrian = $this->where('poli_id', $poliId)
            ->where('created_at >=', $today . ' 00:00:00')
            ->where('created_at <=', $today . ' 23:59:59')
            ->orderBy('id', 'DESC')
            ->first();

        $sequence = 1;
        if ($lastAntrian) {
            // Extract sequence from nomor (e.g., A-005 -> 5)
            $parts = explode('-', $lastAntrian['nomor']);
            if (isset($parts[1])) {
                $sequence = (int) $parts[1] + 1;
            }
        }

        return sprintf('%s-%03d', $poli['prefix'], $sequence);
    }

    /**
     * Create new antrian
     */
    public function createAntrian(int $poliId, ?string $namaPasien = null): array
    {
        $nomor = $this->generateNomor($poliId);

        $data = [
            'poli_id' => $poliId,
            'nomor' => $nomor,
            'status' => self::STATUS_WAITING,
            'nama_pasien' => $namaPasien,
            'waktu_ambil' => date('Y-m-d H:i:s'),
        ];

        $id = $this->insert($data);

        return $this->find($id);
    }

    /**
     * Call next antrian
     */
    public function callNext(int $poliId, int $callerId): ?array
    {
        // First, complete current serving if exists
        $current = $this->getCurrentServing($poliId);
        if ($current) {
            $this->update($current['id'], [
                'status' => self::STATUS_COMPLETED,
                'waktu_selesai' => date('Y-m-d H:i:s'),
                'selesai_oleh' => $callerId,
            ]);
        }

        // Get next waiting
        $next = $this->where('poli_id', $poliId)
            ->where('status', self::STATUS_WAITING)
            ->orderBy('id', 'ASC')
            ->first();

        if ($next) {
            $this->update($next['id'], [
                'status' => self::STATUS_CALLED,
                'waktu_panggil' => date('Y-m-d H:i:s'),
                'dipanggil_oleh' => $callerId,
            ]);

            // Log to antrian_log
            $this->logAntrian($next);

            return $this->find($next['id']);
        }

        return null;
    }

    /**
     * Recall current antrian
     */
    public function recall(int $poliId): ?array
    {
        $current = $this->getCurrentServing($poliId);

        if ($current) {
            $this->update($current['id'], [
                'waktu_panggil' => date('Y-m-d H:i:s'),
            ]);

            return $this->find($current['id']);
        }

        return null;
    }

    /**
     * Complete current antrian
     */
    public function complete($antrianId, $userId): bool
    {
        // Convert to int if needed
        $antrianId = (int) $antrianId;
        $userId = (int) $userId;

        return $this->update($antrianId, [
            'status' => self::STATUS_COMPLETED,
            'waktu_selesai' => date('Y-m-d H:i:s'),
            'selesai_oleh' => $userId,
        ]);
    }

    /**
     * Skip antrian
     */
    public function skip($antrianId): bool
    {
        return $this->update($antrianId, [
            'status' => self::STATUS_SKIPPED,
        ]);
    }

    /**
     * Log antrian to antrian_log table
     */
    private function logAntrian(array $antrian): void
    {
        $db = \Config\Database::connect();
        $db->table('antrian_log')->insert([
            'poli_id' => $antrian['poli_id'],
            'nomor' => $antrian['nomor'],
            'status' => $antrian['status'],
            'tanggal' => date('Y-m-d'),
            'waktu_ambil' => $antrian['waktu_ambil'],
            'waktu_panggil' => $antrian['waktu_panggil'],
            'created_at' => date('Y-m-d H:i:s'),
        ]);
    }

    /**
     * Auto-reset old queues (called automatically)
     * - Skip waiting queues from previous days
     * - Complete serving queues from previous days
     */
    public function autoResetOldQueues(): bool
    {
        $today = date('Y-m-d');
        $affected = 0;

        // Skip all waiting queues from previous days
        $oldWaiting = $this->where('status', self::STATUS_WAITING)
            ->where('created_at <', $today . ' 00:00:00')
            ->findAll();

        foreach ($oldWaiting as $antrian) {
            $this->update($antrian['id'], ['status' => self::STATUS_SKIPPED]);
            $this->logAntrian($antrian);
            $affected++;
        }

        // Complete all serving/called queues from previous days
        $oldServing = $this->whereIn('status', [self::STATUS_CALLED, self::STATUS_SERVING])
            ->where('created_at <', $today . ' 00:00:00')
            ->findAll();

        foreach ($oldServing as $antrian) {
            $this->update($antrian['id'], [
                'status' => self::STATUS_COMPLETED,
                'waktu_selesai' => $antrian['created_at'],
            ]);
            $this->logAntrian($antrian);
            $affected++;
        }

        return $affected > 0;
    }

    /**
     * Reset antrian for a poli
     */
    public function resetPoli(int $poliId): bool
    {
        // Move completed to log before deleting
        $completed = $this->where('poli_id', $poliId)
            ->where('status', self::STATUS_COMPLETED)
            ->findAll();

        foreach ($completed as $antrian) {
            $this->logAntrian($antrian);
        }

        // Delete all antrian for this poli
        return $this->where('poli_id', $poliId)->delete() !== false;
    }

    /**
     * Get antrian with poli details
     */
    public function getWithPoli(int $antrianId): ?array
    {
        return $this->select('antrian.*, poli.nama as poli_nama, poli.prefix')
            ->join('poli', 'poli.id = antrian.poli_id')
            ->where('antrian.id', $antrianId)
            ->first();
    }

    /**
     * Get today's statistics
     */
    public function getTodayStats(int $poliId = null): array
    {
        $today = date('Y-m-d');
        $builder = $this->select('COUNT(*) as count, status')
            ->where('created_at >=', $today . ' 00:00:00')
            ->where('created_at <=', $today . ' 23:59:59')
            ->groupBy('status');

        if ($poliId) {
            $builder->where('poli_id', $poliId);
        }

        $results = $builder->findAll();

        $stats = [
            'total' => 0,
            'waiting' => 0,
            'serving' => 0,
            'completed' => 0,
            'skipped' => 0,
        ];

        foreach ($results as $row) {
            $stats['total'] += $row['count'];
            $stats[$row['status']] = $row['count'];
        }

        return $stats;
    }
}
