<?php

declare(strict_types=1);

namespace App\Models;

use CodeIgniter\Model;

class UserPoliModel extends Model
{
    protected $table = 'user_poli';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'user_id',
        'poli_id',
    ];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    /**
     * Get poli IDs for a user
     */
    public function getUserPoliIds(int $userId): array
    {
        $results = $this->where('user_id', $userId)->findAll();

        return array_column($results, 'poli_id');
    }

    /**
     * Get poli details for a user
     */
    public function getUserPoli(int $userId): array
    {
        return $this->select('poli.*')
            ->join('poli', 'poli.id = user_poli.poli_id')
            ->where('user_poli.user_id', $userId)
            ->findAll();
    }

    /**
     * Assign poli to user
     */
    public function assignPoli(int $userId, int $poliId): bool
    {
        // Check if already assigned
        $existing = $this->where('user_id', $userId)
            ->where('poli_id', $poliId)
            ->first();

        if ($existing) {
            return true; // Already assigned
        }

        return $this->insert([
            'user_id' => $userId,
            'poli_id' => $poliId,
        ]) !== false;
    }

    /**
     * Remove poli assignment from user
     */
    public function removePoli(int $userId, int $poliId): bool
    {
        return $this->where('user_id', $userId)
            ->where('poli_id', $poliId)
            ->delete() !== false;
    }

    /**
     * Remove all poli assignments for a user
     */
    public function removeAllPoli(int $userId): bool
    {
        return $this->where('user_id', $userId)->delete() !== false;
    }

    /**
     * Get users assigned to a poli
     */
    public function getPoliUsers(int $poliId): array
    {
        return $this->select('users.*')
            ->join('users', 'users.id = user_poli.user_id')
            ->where('user_poli.poli_id', $poliId)
            ->findAll();
    }
}
