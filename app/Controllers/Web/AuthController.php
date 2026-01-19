<?php

declare(strict_types=1);

namespace App\Controllers\Web;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\AuditLogModel;

class AuthController extends BaseController
{
    protected UserModel $userModel;
    protected AuditLogModel $auditLogModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->auditLogModel = new AuditLogModel();
    }

    /**
     * Show login form
     */
    public function loginForm()
    {
        // If already logged in, redirect to dashboard
        if (session()->get('user_id')) {
            $redirectUrl = (session()->get('user_role') === 'admin') ? '/admin' : '/monitor';
            return redirect()->to($redirectUrl);
        }

        return view('auth/login');
    }

    /**
     * Handle login (also used by API)
     */
    public function login()
    {
        $rules = [
            'username' => 'required|min_length[3]',
            'password' => 'required|min_length[6]',
        ];

        if (!$this->validate($rules)) {
            if ($this->request->isAJAX()) {
                return $this->response->setStatusCode(422)->setJSON([
                    'success' => false,
                    'message' => 'Validation error',
                    'errors' => $this->validator->getErrors(),
                ]);
            }

            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        $user = $this->userModel->findByUsername($username);

        if (!$user || !$this->userModel->verifyPassword($password, $user['password'])) {
            if ($this->request->isAJAX()) {
                return $this->response->setStatusCode(401)->setJSON([
                    'success' => false,
                    'message' => 'Username atau password salah',
                ]);
            }

            return redirect()->back()->withInput()->with('error', 'Username atau password salah');
        }

        if (!$user['aktif']) {
            if ($this->request->isAJAX()) {
                return $this->response->setStatusCode(403)->setJSON([
                    'success' => false,
                    'message' => 'Akun Anda dinonaktifkan',
                ]);
            }

            return redirect()->back()->withInput()->with('error', 'Akun Anda dinonaktifkan');
        }

        // Update last login
        $this->userModel->updateLastLogin($user['id']);

        // Set session
        session()->set([
            'user_id' => $user['id'],
            'username' => $user['username'],
            'nama_lengkap' => $user['nama_lengkap'],
            'user_role' => $user['role'],
        ]);

        // Audit Log: Login
        $this->auditLogModel->log(
            AuditLogModel::ACTION_LOGIN,
            AuditLogModel::ENTITY_USER,
            (int)$user['id'],
            'User logged in'
        );

        if ($this->request->isAJAX()) {
            $userWithPoli = $this->userModel->getUserWithPoli($user['id']);
            $redirectUrl = ($user['role'] === 'admin') ? '/admin' : '/monitor';
            
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Login berhasil',
                'redirect' => $redirectUrl,
                'data' => [
                    'user' => [
                        'id' => $user['id'],
                        'username' => $user['username'],
                        'nama_lengkap' => $user['nama_lengkap'],
                        'role' => $user['role'],
                    ],
                    'polis' => $userWithPoli['polis'] ?? [],
                ],
            ]);
        }

        $redirectUrl = ($user['role'] === 'admin') ? '/admin' : '/monitor';
        return redirect()->to($redirectUrl)->with('success', 'Login berhasil');
    }

    /**
     * Logout
     */
    public function logout()
    {
        // Audit Log: Logout
        if (session()->get('user_id')) {
            $this->auditLogModel->log(
                AuditLogModel::ACTION_LOGOUT,
                AuditLogModel::ENTITY_USER,
                (int)session()->get('user_id'),
                'User logged out'
            );
        }

        session()->destroy();
        return redirect()->to('/auth/login')->with('success', 'Logout berhasil');
    }
}
