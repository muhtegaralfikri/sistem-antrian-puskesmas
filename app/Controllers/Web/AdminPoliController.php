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
            ];

            if (!$this->validate($rules)) {
                return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
            }

            $data = [
                'nama' => $this->request->getPost('nama'),
                'prefix' => strtoupper($this->request->getPost('prefix')),
                'aktif' => 1,
            ];

            $this->poliModel->insert($data);

            return redirect()->to('/admin/poli')->with('success', 'Poli berhasil ditambahkan');
        }

        return view('admin/poli_create');
    }

    public function edit($id)
    {
        $poli = $this->poliModel->find($id);

        if (!$poli) {
            return redirect()->to('/admin/poli')->with('error', 'Poli tidak ditemukan');
        }

        if ($this->request->getMethod() === 'POST') {
            $rules = [
                'nama' => 'required|min_length[2]|max_length[100]',
                'prefix' => "required|min_length[1]|max_length[5]|is_unique[poli.prefix,id,{$id}]",
            ];

            if (!$this->validate($rules)) {
                return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
            }

            $data = [
                'nama' => $this->request->getPost('nama'),
                'prefix' => strtoupper($this->request->getPost('prefix')),
                'aktif' => $this->request->getPost('aktif') ? 1 : 0,
            ];

            $this->poliModel->update($id, $data);

            return redirect()->to('/admin/poli')->with('success', 'Poli berhasil diperbarui');
        }

        $data = [
            'title' => 'Edit Poli',
            'poli' => $poli,
        ];

        return view('admin/poli_edit', $data);
    }

    public function delete($id)
    {
        $poli = $this->poliModel->find($id);

        if (!$poli) {
            return redirect()->to('/admin/poli')->with('error', 'Poli tidak ditemukan');
        }

        // Hapus relasi user_poli
        $this->userPoliModel->where('poli_id', $id)->delete();

        // Hapus poli
        $this->poliModel->delete($id);

        return redirect()->to('/admin/poli')->with('success', 'Poli berhasil dihapus');
    }
}
