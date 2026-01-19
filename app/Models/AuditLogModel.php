<?php

declare(strict_types=1);

namespace App\Models;

use CodeIgniter\Model;

class AuditLogModel extends Model
{
    protected $table = 'audit_log';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'user_id',
        'username',
        'action',
        'entity_type',
        'entity_id',
        'description',
        'old_values',
        'new_values',
        'ip_address',
        'user_agent',
        'created_at',
    ];

    // Dates
    protected $useTimestamps = false;
    protected $createdField = 'created_at';

    // Action constants
    public const ACTION_LOGIN = 'LOGIN';
    public const ACTION_LOGOUT = 'LOGOUT';
    public const ACTION_CREATE = 'CREATE';
    public const ACTION_UPDATE = 'UPDATE';
    public const ACTION_DELETE = 'DELETE';
    public const ACTION_CALL = 'CALL';
    public const ACTION_RECALL = 'RECALL';
    public const ACTION_COMPLETE = 'COMPLETE';
    public const ACTION_SKIP = 'SKIP';
    public const ACTION_RESET = 'RESET';

    // Entity type constants
    public const ENTITY_USER = 'User';
    public const ENTITY_POLI = 'Poli';
    public const ENTITY_ANTRIAN = 'Antrian';
    public const ENTITY_SETTINGS = 'Settings';

    /**
     * Log action to audit log
     *
     * @param string $action Action performed
     * @param string|null $entityType Type of entity
     * @param int|null $entityId ID of entity
     * @param string|null $description Description of action
     * @param array|null $oldValues Old values before change
     * @param array|null $newValues New values after change
     * @return int Log ID
     */
    public function log(
        string $action,
        ?string $entityType = null,
        ?int $entityId = null,
        ?string $description = null,
        ?array $oldValues = null,
        ?array $newValues = null
    ): int {
        $session = session();
        $request = service('request');

        $data = [
            'user_id' => $session->get('user_id'),
            'username' => $session->get('username') ?? 'System',
            'action' => $action,
            'entity_type' => $entityType,
            'entity_id' => $entityId,
            'description' => $description,
            'old_values' => $oldValues ? json_encode($oldValues) : null,
            'new_values' => $newValues ? json_encode($newValues) : null,
            'ip_address' => $request->getIPAddress(),
            'user_agent' => (string) $request->getUserAgent(),
            'created_at' => date('Y-m-d H:i:s'),
        ];

        return $this->insert($data);
    }

    /**
     * Get logs by user ID
     *
     * @param int $userId User ID
     * @param int $limit Limit number of results
     * @return array
     */
    public function getByUser(int $userId, int $limit = 50): array
    {
        return $this->where('user_id', $userId)
            ->orderBy('created_at', 'DESC')
            ->limit($limit)
            ->findAll();
    }

    /**
     * Get logs by entity
     *
     * @param string $entityType Entity type
     * @param int $entityId Entity ID
     * @param int $limit Limit number of results
     * @return array
     */
    public function getByEntity(string $entityType, int $entityId, int $limit = 50): array
    {
        return $this->where('entity_type', $entityType)
            ->where('entity_id', $entityId)
            ->orderBy('created_at', 'DESC')
            ->limit($limit)
            ->findAll();
    }

    /**
     * Get logs by action
     *
     * @param string $action Action type
     * @param int $limit Limit number of results
     * @return array
     */
    public function getByAction(string $action, int $limit = 50): array
    {
        return $this->where('action', $action)
            ->orderBy('created_at', 'DESC')
            ->limit($limit)
            ->findAll();
    }

    /**
     * Get recent logs
     *
     * @param int $limit Limit number of results
     * @return array
     */
    public function getRecent(int $limit = 50): array
    {
        return $this->orderBy('created_at', 'DESC')
            ->limit($limit)
            ->findAll();
    }

    /**
     * Get logs by date range
     *
     * @param string $startDate Start date (Y-m-d)
     * @param string $endDate End date (Y-m-d)
     * @return array
     */
    public function getByDateRange(string $startDate, string $endDate): array
    {
        return $this->where('created_at >=', $startDate . ' 00:00:00')
            ->where('created_at <=', $endDate . ' 23:59:59')
            ->orderBy('created_at', 'DESC')
            ->findAll();
    }

    /**
     * Clean old logs (keep last N days)
     *
     * @param int $days Number of days to keep
     * @return int Number of deleted records
     */
    public function cleanOldLogs(int $days = 90): int
    {
        $cutoffDate = date('Y-m-d H:i:s', strtotime("-{$days} days"));

        return $this->where('created_at <', $cutoffDate)->delete();
    }
}
