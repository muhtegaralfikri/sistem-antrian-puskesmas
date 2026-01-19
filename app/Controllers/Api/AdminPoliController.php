<?php

declare(strict_types=1);

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\PoliModel;
use App\Models\AuditLogModel;

class AdminPoliController extends BaseController
{
    protected PoliModel $poliModel;
    protected AuditLogModel $auditLogModel;

    public function __construct()
    {
        $this->poliModel = new PoliModel();
        $this->auditLogModel = new AuditLogModel();
    }

    /**
     * Get all poli
     * GET /api/v1/admin/poli
     */
    public function index()
    {
        $polis = $this->poliModel->orderBy('urutan', 'ASC')->findAll();

        return $this->response->setJSON([
            'success' => true,
            'data' => $polis,
        ]);
    }

    /**
     * Create new poli
     * POST /api/v1/admin/poli
     */
    public function create()
    {
        $rules = [
            'nama' => 'required|min_length[3]|max_length[100]',
            'kode' => 'required|min_length[2]|max_length[10]|is_unique[poli.kode]',
            'prefix' => 'required|min_length[1]|max_length[10]|is_unique[poli.prefix]',
        ];

        if (!$this->validate($rules)) {
            return $this->response->setStatusCode(422)->setJSON([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $this->validator->getErrors(),
            ]);
        }

        $data = [
            'nama' => $this->request->getPost('nama'),
            'kode' => strtoupper($this->request->getPost('kode')),
            'prefix' => strtoupper($this->request->getPost('prefix')),
            'aktif' => 1,
            'urutan' => $this->request->getPost('urutan') ?? 0,
        ];

        $id = $this->poliModel->insert($data);

        if ($id === false) {
            return $this->response->setStatusCode(500)->setJSON([
                'success' => false,
                'message' => 'Failed to create poli',
            ]);
        }

        $poli = $this->poliModel->find($id);

        // Audit Log: Create Poli
        $this->auditLogModel->log(
            AuditLogModel::ACTION_CREATE,
            AuditLogModel::ENTITY_POLI,
            (int)$id,
            "Created Poli: {$data['nama']}",
            null,
            $data
        );

        return $this->response->setStatusCode(201)->setJSON([
            'success' => true,
            'message' => 'Poli berhasil ditambahkan',
            'data' => $poli,
        ]);
    }

    /**
     * Update poli
     * PUT /api/v1/admin/poli/{id}
     */
    public function update($id)
    {
        $poli = $this->poliModel->find($id);
        if (!$poli) {
            return $this->response->setStatusCode(404)->setJSON([
                'success' => false,
                'message' => 'Poli not found',
            ]);
        }

        $rules = [
            'nama' => 'required|min_length[3]|max_length[100]',
            'kode' => "required|min_length[2]|max_length[10]|is_unique[poli.kode,id,{$id}]",
            'prefix' => "required|min_length[1]|max_length[10]|is_unique[poli.prefix,id,{$id}]",
        ];

        if (!$this->validate($rules)) {
            return $this->response->setStatusCode(422)->setJSON([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $this->validator->getErrors(),
            ]);
        }

        $data = [
            'nama' => $this->request->getRawInput()['nama'] ?? $this->request->getPost('nama'),
            'kode' => strtoupper($this->request->getRawInput()['kode'] ?? $this->request->getPost('kode')),
            'prefix' => strtoupper($this->request->getRawInput()['prefix'] ?? $this->request->getPost('prefix')),
            'aktif' => $this->request->getRawInput()['aktif'] ?? $this->request->getPost('aktif') ?? 1,
            'urutan' => $this->request->getRawInput()['urutan'] ?? $this->request->getPost('urutan') ?? 0,
        ];

        $updated = $this->poliModel->update($id, $data);

        if ($updated === false) {
            return $this->response->setStatusCode(500)->setJSON([
                'success' => false,
                'message' => 'Failed to update poli',
            ]);
        }

        // Audit Log: Update Poli
        $this->auditLogModel->log(
            AuditLogModel::ACTION_UPDATE,
            AuditLogModel::ENTITY_POLI,
            (int)$id,
            "Updated Poli: {$poli['nama']}",
            $poli, // Old values
            $data  // New values
        );

        $poli = $this->poliModel->find($id);

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Poli berhasil diupdate',
            'data' => $poli,
        ]);
    }

    /**
     * Delete poli
     * DELETE /api/v1/admin/poli/{id}
     */
    public function delete($id)
    {
        $poli = $this->poliModel->find($id);
        if (!$poli) {
            return $this->response->setStatusCode(404)->setJSON([
                'success' => false,
                'message' => 'Poli not found',
            ]);
        }

        $deleted = $this->poliModel->delete($id);

        if ($deleted === false) {
            return $this->response->setStatusCode(500)->setJSON([
                'success' => false,
                'message' => 'Failed to delete poli',
            ]);
        }

        // Audit Log: Delete Poli
        $this->auditLogModel->log(
            AuditLogModel::ACTION_DELETE,
            AuditLogModel::ENTITY_POLI,
            (int)$id,
            "Deleted Poli: {$poli['nama']}",
            $poli
        );

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Poli berhasil dihapus',
        ]);
    }
}
