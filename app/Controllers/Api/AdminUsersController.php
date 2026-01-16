<?php

declare(strict_types=1);

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\UserModel;

class AdminUsersController extends BaseController
{
    protected UserModel $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    /**
     * Get all users
     * GET /api/v1/admin/users
     */
    public function index()
    {
        $users = $this->userModel->orderBy('nama_lengkap', 'ASC')->findAll();

        // Add poli for each user
        foreach ($users as &$user) {
            $userWithPoli = $this->userModel->getUserWithPoli($user['id']);
            $user['polis'] = $userWithPoli['polis'] ?? [];
            // Remove password from response
            unset($user['password']);
        }

        return $this->response->setJSON([
            'success' => true,
            'data' => $users,
        ]);
    }

    /**
     * Create new user
     * POST /api/v1/admin/users
     */
    public function create()
    {
        $rules = [
            'username' => 'required|min_length[3]|max_length[50]|is_unique[users.username]',
            'password' => 'required|min_length[6]',
            'nama_lengkap' => 'required|min_length[3]|max_length[100]',
            'role' => 'required|in_list[admin,petugas]',
        ];

        if (!$this->validate($rules)) {
            return $this->response->setStatusCode(422)->setJSON([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $this->validator->getErrors(),
            ]);
        }

        $data = [
            'username' => $this->request->getPost('username'),
            'password' => $this->request->getPost('password'),
            'nama_lengkap' => $this->request->getPost('nama_lengkap'),
            'email' => $this->request->getPost('email'),
            'role' => $this->request->getPost('role'),
            'aktif' => 1,
        ];

        $poliIds = $this->request->getPost('poli_ids');
        if (is_string($poliIds)) {
            $poliIds = explode(',', $poliIds);
        }

        $userId = $this->userModel->createUserWithPoli($data, $poliIds ?? []);

        if (!$userId) {
            return $this->response->setStatusCode(500)->setJSON([
                'success' => false,
                'message' => 'Failed to create user',
            ]);
        }

        $user = $this->userModel->getUserWithPoli($userId);
        unset($user['password']);

        return $this->response->setStatusCode(201)->setJSON([
            'success' => true,
            'message' => 'User berhasil ditambahkan',
            'data' => $user,
        ]);
    }

    /**
     * Update user
     * PUT /api/v1/admin/users/{id}
     */
    public function update($id)
    {
        $user = $this->userModel->find($id);
        if (!$user) {
            return $this->response->setStatusCode(404)->setJSON([
                'success' => false,
                'message' => 'User not found',
            ]);
        }

        $rawInput = $this->request->getRawInput();

        $rules = [
            'username' => "required|min_length[3]|max_length[50]|is_unique[users.username,id,{$id}]",
            'nama_lengkap' => 'required|min_length[3]|max_length[100]',
            'role' => 'required|in_list[admin,petugas]',
        ];

        if (!$this->validate($rules)) {
            return $this->response->setStatusCode(422)->setJSON([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $this->validator->getErrors(),
            ]);
        }

        $data = [
            'username' => $rawInput['username'] ?? $this->request->getPost('username'),
            'nama_lengkap' => $rawInput['nama_lengkap'] ?? $this->request->getPost('nama_lengkap'),
            'email' => $rawInput['email'] ?? $this->request->getPost('email'),
            'role' => $rawInput['role'] ?? $this->request->getPost('role'),
            'aktif' => $rawInput['aktif'] ?? $this->request->getPost('aktif') ?? 1,
        ];

        // Optional password update
        if (!empty($rawInput['password'])) {
            $data['password'] = $rawInput['password'];
        }

        $poliIds = $rawInput['poli_ids'] ?? $this->request->getPost('poli_ids');
        if (is_string($poliIds)) {
            $poliIds = explode(',', $poliIds);
        } else {
            $poliIds = [];
        }

        $updated = $this->userModel->updateUserWithPoli($id, $data, $poliIds);

        if (!$updated) {
            return $this->response->setStatusCode(500)->setJSON([
                'success' => false,
                'message' => 'Failed to update user',
            ]);
        }

        $user = $this->userModel->getUserWithPoli($id);
        unset($user['password']);

        return $this->response->setJSON([
            'success' => true,
            'message' => 'User berhasil diupdate',
            'data' => $user,
        ]);
    }

    /**
     * Delete user
     * DELETE /api/v1/admin/users/{id}
     */
    public function delete($id)
    {
        // Prevent deleting self
        $session = session();
        $currentUserId = $session->get('user_id');
        if ($id == $currentUserId) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'Tidak dapat menghapus akun sendiri',
            ]);
        }

        $user = $this->userModel->find($id);
        if (!$user) {
            return $this->response->setStatusCode(404)->setJSON([
                'success' => false,
                'message' => 'User not found',
            ]);
        }

        $deleted = $this->userModel->deleteUserWithPoli($id);

        if (!$deleted) {
            return $this->response->setStatusCode(500)->setJSON([
                'success' => false,
                'message' => 'Failed to delete user',
            ]);
        }

        return $this->response->setJSON([
            'success' => true,
            'message' => 'User berhasil dihapus',
        ]);
    }
}
