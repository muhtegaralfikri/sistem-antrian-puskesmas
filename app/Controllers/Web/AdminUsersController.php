<?php

declare(strict_types=1);

namespace App\Controllers\Web;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\UserPoliModel;
use App\Models\PoliModel;

class AdminUsersController extends BaseController
{
    protected UserModel $userModel;
    protected UserPoliModel $userPoliModel;
    protected PoliModel $poliModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->userPoliModel = new UserPoliModel();
        $this->poliModel = new PoliModel();
    }

    public function index()
    {
        $users = $this->userModel->select('users.*, poli.nama as poli_nama')
            ->join('user_poli', 'user_poli.user_id = users.id', 'left')
            ->join('poli', 'poli.id = user_poli.poli_id', 'left')
            ->orderBy('users.id', 'DESC')
            ->findAll();

        $data = [
            'title' => 'Kelola Pengguna',
            'users' => $users,
        ];

        return view('admin/users', $data);
    }

    public function create()
    {
        if ($this->request->getMethod() === 'POST') {
            $rules = [
                'username' => 'required|min_length[3]|max_length[50]|is_unique[users.username]',
                'password' => 'required|min_length[6]',
                'nama_lengkap' => 'required|min_length[2]|max_length[100]',
                'email' => 'permit_empty|valid_email',
                'role' => 'required|in_list[admin,petugas]',
                'poli_id' => 'permit_empty|numeric',
            ];

            if (!$this->validate($rules)) {
                return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
            }

            // Hash password
            $password = $this->request->getPost('password');
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Insert user
            $userId = $this->userModel->insert([
                'username' => $this->request->getPost('username'),
                'password' => $hashedPassword,
                'nama_lengkap' => $this->request->getPost('nama_lengkap'),
                'email' => $this->request->getPost('email') ?: null,
                'role' => $this->request->getPost('role'),
                'aktif' => 1,
            ]);

            // Assign poli if petugas and poli_id is provided
            if ($this->request->getPost('role') === 'petugas') {
                $poliId = $this->request->getPost('poli_id');
                if ($poliId) {
                    $this->userPoliModel->insert([
                        'user_id' => $userId,
                        'poli_id' => $poliId,
                    ]);
                }
            }

            return redirect()->to('/admin/users')->with('success', 'Pengguna berhasil ditambahkan');
        }

        $data = [
            'title' => 'Tambah Pengguna',
            'polis' => $this->poliModel->getAllPoli(),
        ];

        return view('admin/users_create', $data);
    }

    public function edit($id)
    {
        $user = $this->userModel->find($id);

        if (!$user) {
            return redirect()->to('/admin/users')->with('error', 'Pengguna tidak ditemukan');
        }

        // Get user poli assignment
        $userPoli = $this->userPoliModel->where('user_id', $id)->first();

        if ($this->request->getMethod() === 'POST') {
            $rules = [
                'username' => "required|min_length[3]|max_length[50]|is_unique[users.username,id,{$id}]",
                'nama_lengkap' => 'required|min_length[2]|max_length[100]',
                'email' => 'permit_empty|valid_email',
                'role' => 'required|in_list[admin,petugas]',
                'poli_id' => 'permit_empty|numeric',
            ];

            // Password validation only if provided
            $password = $this->request->getPost('password');
            if ($password) {
                $rules['password'] = 'min_length[6]';
            }

            if (!$this->validate($rules)) {
                return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
            }

            // Update user data
            $updateData = [
                'username' => $this->request->getPost('username'),
                'nama_lengkap' => $this->request->getPost('nama_lengkap'),
                'email' => $this->request->getPost('email') ?: null,
                'role' => $this->request->getPost('role'),
                'aktif' => $this->request->getPost('aktif') ? 1 : 0,
            ];

            // Update password if provided
            if ($password) {
                $updateData['password'] = password_hash($password, PASSWORD_DEFAULT);
            }

            $this->userModel->update($id, $updateData);

            // Update poli assignment
            if ($this->request->getPost('role') === 'petugas') {
                // Delete existing assignment
                $this->userPoliModel->where('user_id', $id)->delete();

                // Add new assignment if poli_id provided
                $poliId = $this->request->getPost('poli_id');
                if ($poliId) {
                    $this->userPoliModel->insert([
                        'user_id' => $id,
                        'poli_id' => $poliId,
                    ]);
                }
            } else {
                // Remove poli assignment if role changed to admin
                $this->userPoliModel->where('user_id', $id)->delete();
            }

            return redirect()->to('/admin/users')->with('success', 'Pengguna berhasil diperbarui');
        }

        $data = [
            'title' => 'Edit Pengguna',
            'user' => $user,
            'user_poli' => $userPoli,
            'polis' => $this->poliModel->getAllPoli(),
        ];

        return view('admin/users_edit', $data);
    }

    public function delete($id)
    {
        $user = $this->userModel->find($id);

        if (!$user) {
            return redirect()->to('/admin/users')->with('error', 'Pengguna tidak ditemukan');
        }

        // Prevent deleting yourself
        if ($user['id'] === session()->get('user_id')) {
            return redirect()->to('/admin/users')->with('error', 'Tidak dapat menghapus pengguna yang sedang login');
        }

        // Delete poli assignment
        $this->userPoliModel->where('user_id', $id)->delete();

        // Delete user
        $this->userModel->delete($id);

        return redirect()->to('/admin/users')->with('success', 'Pengguna berhasil dihapus');
    }
}
