<?php

declare(strict_types=1);

namespace App\Models;

use CodeIgniter\Model;

class PoliModel extends Model
{
    protected $table = 'poli';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'nama',
        'kode',
        'prefix',
        'aktif',
        'urutan',
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // Validation
    protected $validationRules = [
        'nama' => 'required|min_length[3]|max_length[100]',
        'kode' => 'required|min_length[2]|max_length[10]|is_unique[poli.kode,id,{id}]',
        'prefix' => 'required|min_length[1]|max_length[10]|is_unique[poli.prefix,id,{id}]',
    ];
    protected $validationMessages = [
        'nama' => [
            'required' => 'Nama poli wajib diisi',
            'min_length' => 'Nama poli minimal 3 karakter',
        ],
        'kode' => [
            'required' => 'Kode poli wajib diisi',
            'is_unique' => 'Kode poli sudah digunakan',
        ],
        'prefix' => [
            'required' => 'Prefix wajib diisi',
            'is_unique' => 'Prefix sudah digunakan',
        ],
    ];

    /**
     * Get all active poli ordered by urutan
     */
    public function getActivePoli(): array
    {
        return $this->where('aktif', 1)
            ->orderBy('urutan', 'ASC')
            ->findAll();
    }

    /**
     * Get poli by prefix
     */
    public function getByPrefix(string $prefix): ?array
    {
        return $this->where('prefix', $prefix)->first();
    }

    /**
     * Get poli with today's queue count
     */
    public function getWithQueueCount(): array
    {
        $polis = $this->getActivePoli();
        $antrianModel = new AntrianModel();

        foreach ($polis as &$poli) {
            $poli['queue_count'] = $antrianModel->getWaitingCount($poli['id']);
            $poli['serving_count'] = $antrianModel->getServingCount($poli['id']);
            $poli['completed_count'] = $antrianModel->getCompletedCount($poli['id']);
        }

        return $polis;
    }

    /**
     * Get current serving number for this poli
     */
    public function getCurrentServing(int $poliId): ?array
    {
        $antrianModel = new AntrianModel();
        return $antrianModel->getCurrentServing($poliId);
    }
}
