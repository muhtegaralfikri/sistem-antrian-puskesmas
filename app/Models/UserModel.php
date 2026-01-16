<?php

declare(strict_types=1);

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'username',
        'password',
        'nama_lengkap',
        'email',
        'role',
        'aktif',
        'last_login',
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // Role constants
    public const ROLE_ADMIN = 'admin';
    public const ROLE_PETUGAS = 'petugas';

    /**
     * Find user by username for login
     */
    public function findByUsername(string $username): ?array
    {
        return $this->where('username', $username)->first();
    }

    /**
     * Verify password
     */
    public function verifyPassword(string $password, string $hash): bool
    {
        return password_verify($password, $hash);
    }

    /**
     * Update last login
     */
    public function updateLastLogin(int $userId): bool
    {
        return $this->update($userId, [
            'last_login' => date('Y-m-d H:i:s'),
        ]) !== false;
    }

    /**
     * Get user with assigned poli
     */
    public function getUserWithPoli(int $userId): ?array
    {
        $user = $this->find($userId);

        if (!$user) {
            return null;
        }

        // Get assigned poli
        $polis = $this->db->table('user_poli')
            ->select('poli.*, user_poli.id as user_poli_id')
            ->join('poli', 'poli.id = user_poli.poli_id')
            ->where('user_poli.user_id', $userId)
            ->where('poli.aktif', 1)
            ->orderBy('poli.urutan', 'ASC')
            ->get()
            ->getResultArray();

        $user['polis'] = $polis;

        return $user;
    }

    /**
     * Get user's poli IDs
     */
    public function getUserPoliIds(int $userId): array
    {
        $results = $this->db->table('user_poli')
            ->select('poli_id')
            ->where('user_id', $userId)
            ->get()
            ->getResultArray();

        return array_column($results, 'poli_id');
    }

    /**
     * Check if user has access to poli
     */
    public function hasPoliAccess(int $userId, int $poliId): bool
    {
        if ($this->isAdmin($userId)) {
            return true;
        }

        return $this->db->table('user_poli')
            ->where('user_id', $userId)
            ->where('poli_id', $poliId)
            ->countAllResults() > 0;
    }

    /**
     * Check if user is admin
     */
    public function isAdmin(int $userId): bool
    {
        $user = $this->find($userId);
        return $user && $user['role'] === self::ROLE_ADMIN;
    }

    /**
     * Get all petugas
     */
    public function getAllPetugas(): array
    {
        return $this->where('role', self::ROLE_PETUGAS)
            ->orderBy('nama_lengkap', 'ASC')
            ->findAll();
    }

    /**
     * Get all admins
     */
    public function getAllAdmins(): array
    {
        return $this->where('role', self::ROLE_ADMIN)
            ->orderBy('nama_lengkap', 'ASC')
            ->findAll();
    }

    /**
     * Assign poli to user
     */
    public function assignPoli(int $userId, array $poliIds): bool
    {
        // First, remove existing assignments
        $this->db->table('user_poli')->where('user_id', $userId)->delete();

        // Then insert new assignments
        if (!empty($poliIds)) {
            $data = [];
            foreach ($poliIds as $poliId) {
                $data[] = [
                    'user_id' => $userId,
                    'poli_id' => $poliId,
                    'created_at' => date('Y-m-d H:i:s'),
                ];
            }
            return $this->db->table('user_poli')->insertBatch($data) !== false;
        }

        return true;
    }

    /**
     * Create new user with poli assignment
     */
    public function createUserWithPoli(array $data, array $poliIds = []): int|bool
    {
        // Hash password
        if (isset($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }

        $this->db->transStart();

        // Insert user
        $this->insert($data);
        $userId = $this->getInsertID();

        // Assign poli
        if (!empty($poliIds)) {
            $this->assignPoli($userId, $poliIds);
        }

        $this->db->transComplete();

        return $this->db->transStatus() ? $userId : false;
    }

    /**
     * Update user with poli assignment
     */
    public function updateUserWithPoli(int $userId, array $data, array $poliIds = []): bool
    {
        // Hash password if provided
        if (isset($data['password']) && !empty($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        } else {
            unset($data['password']);
        }

        $this->db->transStart();

        // Update user
        $this->update($userId, $data);

        // Update poli assignment (only for petugas)
        $user = $this->find($userId);
        if ($user && $user['role'] === self::ROLE_PETUGAS) {
            $this->assignPoli($userId, $poliIds);
        }

        $this->db->transComplete();

        return $this->db->transStatus();
    }

    /**
     * Delete user with poli assignments
     */
    public function deleteUserWithPoli(int $userId): bool
    {
        $this->db->transStart();

        // Delete poli assignments (cascade should handle this, but let's be sure)
        $this->db->table('user_poli')->where('user_id', $userId)->delete();

        // Delete user
        $this->delete($userId);

        $this->db->transComplete();

        return $this->db->transStatus();
    }
}
