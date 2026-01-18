<?php

declare(strict_types=1);

namespace App\Controllers\Web;

use App\Controllers\BaseController;
use App\Models\PoliModel;
use App\Models\UserPoliModel;

class AdminPoliController extends BaseController
{
    protected PoliModel $poliModel;
    protected UserPoliModel $userPoliModel;

    public function __construct()
    {
        $this->poliModel = new PoliModel();
        $this->userPoliModel = new UserPoliModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Kelola Poli',
            'polis' => $this->poliModel->getAllPoli(),
        ];

        return view('admin/poli', $data);
    }

    public function create()
    {
        if ($this->request->getMethod() === 'POST') {
            $rules = [
                'nama' => 'required|min_length[2]|max_length[100]',
                'prefix' => 'required|min_length[1]|max_length[5]|is_unique[poli.prefix]',
                'kode' => 'required|min_length[2]|max_length[10]|is_unique[poli.kode]',
            ];

            if (!$this->validate($rules)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $this->validator->getErrors()
                ]);
            }

            $data = [
                'nama' => $this->request->getPost('nama'),
                'prefix' => strtoupper($this->request->getPost('prefix')),
                'kode' => strtoupper($this->request->getPost('kode')),
                'urutan' => $this->request->getPost('urutan'),
                'aktif' => $this->request->getPost('aktif'),
            ];

            if ($this->poliModel->skipValidation(true)->insert($data)) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Poli berhasil ditambahkan'
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Gagal menyimpan data ke database'
                ]);
            }
        }

        return view('admin/poli_create');
    }

    public function update($id)
    {
        $poli = $this->poliModel->find($id);

        if (!$poli) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Poli tidak ditemukan'
            ]);
        }

        $rules = [
            'nama' => 'required|min_length[2]|max_length[100]',
            'prefix' => "required|min_length[1]|max_length[5]|is_unique[poli.prefix,id,{$id}]",
            'kode' => "required|min_length[2]|max_length[10]|is_unique[poli.kode,id,{$id}]",
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $this->validator->getErrors()
            ]);
        }

        $data = [
            'nama' => $this->request->getPost('nama'),
            'prefix' => strtoupper($this->request->getPost('prefix')),
            'kode' => strtoupper($this->request->getPost('kode')),
            'urutan' => $this->request->getPost('urutan'),
            'aktif' => $this->request->getPost('aktif'),
        ];

        if ($this->poliModel->skipValidation(true)->update($id, $data)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Poli berhasil diperbarui'
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Gagal memperbarui data database'
            ]);
        }
    }

    public function delete($id)
    {
        $poli = $this->poliModel->find($id);

        if (!$poli) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Poli tidak ditemukan'
            ]);
        }

        // Hapus relasi user_poli
        $this->userPoliModel->where('poli_id', $id)->delete();

        // Hapus poli
        $this->poliModel->delete($id);

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Poli berhasil dihapus'
        ]);
    }
}
