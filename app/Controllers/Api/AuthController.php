<?php

declare(strict_types=1);

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\PoliModel;

class AuthController extends BaseController
{
    protected UserModel $userModel;
    protected PoliModel $poliModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->poliModel = new PoliModel();
    }

    /**
     * Login endpoint
     * POST /api/v1/auth/login
     */
    public function login()
    {
        $rules = [
            'username' => 'required|min_length[3]',
            'password' => 'required|min_length[6]',
        ];

        if (!$this->validate($rules)) {
            return $this->response->setStatusCode(422)->setJSON([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $this->validator->getErrors(),
            ]);
        }

        $username = $this->request->getVar('username');
        $password = $this->request->getVar('password');

        $user = $this->userModel->findByUsername($username);

        if (!$user || !$this->userModel->verifyPassword($password, $user['password'])) {
            return $this->response->setStatusCode(401)->setJSON([
                'success' => false,
                'message' => 'Username atau password salah',
            ]);
        }

        if (!$user['aktif']) {
            return $this->response->setStatusCode(403)->setJSON([
                'success' => false,
                'message' => 'Akun Anda dinonaktifkan',
            ]);
        }

        // Update last login
        $this->userModel->updateLastLogin($user['id']);

        // Set session
        $session = session();
        $session->set([
            'user_id' => $user['id'],
            'username' => $user['username'],
            'nama_lengkap' => $user['nama_lengkap'],
            'user_role' => $user['role'],
        ]);

        // Get user's poli
        $userWithPoli = $this->userModel->getUserWithPoli($user['id']);

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Login berhasil',
            'data' => [
                'user' => [
                    'id' => $user['id'],
                    'username' => $user['username'],
                    'nama_lengkap' => $user['nama_lengkap'],
                    'email' => $user['email'],
                    'role' => $user['role'],
                ],
                'polis' => $userWithPoli['polis'] ?? [],
            ],
        ]);
    }

    /**
     * Get current user info
     * GET /api/v1/auth/me
     */
    public function me()
    {
        $session = session();
        $userId = $session->get('user_id');

        if (!$userId) {
            return $this->response->setStatusCode(401)->setJSON([
                'success' => false,
                'message' => 'Unauthorized',
            ]);
        }

        $userWithPoli = $this->userModel->getUserWithPoli($userId);

        if (!$userWithPoli) {
            return $this->response->setStatusCode(404)->setJSON([
                'success' => false,
                'message' => 'User not found',
            ]);
        }

        return $this->response->setJSON([
            'success' => true,
            'data' => [
                'user' => [
                    'id' => $userWithPoli['id'],
                    'username' => $userWithPoli['username'],
                    'nama_lengkap' => $userWithPoli['nama_lengkap'],
                    'email' => $userWithPoli['email'],
                    'role' => $userWithPoli['role'],
                ],
                'polis' => $userWithPoli['polis'] ?? [],
            ],
        ]);
    }

    /**
     * Logout endpoint
     * POST /api/v1/auth/logout
     */
    public function logout()
    {
        $session = session();
        $session->destroy();

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Logout berhasil',
        ]);
    }
}
