<?php

declare(strict_types=1);

namespace App\Validation;

use CodeIgniter\HTTP\IncomingRequest;

/**
 * Custom Password Validation Rules
 *
 * Aturan validasi password untuk keamanan:
 * - Minimal 8 karakter
 * - Minimal 1 huruf kapital
 * - Minimal 1 huruf kecil
 * - Minimal 1 angka
 * - Minimal 1 karakter spesial
 */
class PasswordRules
{
    /**
     * Validasi kompleksitas password
     *
     * @param string $password Password yang akan divalidasi
     * @param string|null $fields Tidak digunakan, required oleh CI4
     * @param array<string, string>|string[] $data Tidak digunakan, required oleh CI4
     * @param bool|float|int|string|null &$error Custom error message
     * @return bool True jika valid, false jika tidak
     */
    public function check_password_strength(
        string $password,
        ?string $fields = null,
        array $data = [],
        &$error = null
    ): bool {
        // Minimal 8 karakter
        if (strlen($password) < 8) {
            $error = 'Password harus minimal 8 karakter';
            return false;
        }

        // Minimal 1 huruf kapital
        if (!preg_match('/[A-Z]/', $password)) {
            $error = 'Password harus mengandung minimal 1 huruf kapital';
            return false;
        }

        // Minimal 1 huruf kecil
        if (!preg_match('/[a-z]/', $password)) {
            $error = 'Password harus mengandung minimal 1 huruf kecil';
            return false;
        }

        // Minimal 1 angka
        if (!preg_match('/[0-9]/', $password)) {
            $error = 'Password harus mengandung minimal 1 angka';
            return false;
        }

        // Minimal 1 karakter spesial
        if (!preg_match('/[!@#$%^&*(),.?":{}|<>]/', $password)) {
            $error = 'Password harus mengandung minimal 1 karakter spesial (!@#$%^&*(),.?":{}|<>)';
            return false;
        }

        return true;
    }

    /**
     * Validasi password tidak sama dengan username
     *
     * @param string $password Password yang akan divalidasi
     * @param string|null $fields Nama field username untuk dibandingkan
     * @param array<string, string>|string[] $data Data form untuk mendapatkan username
     * @param bool|float|int|string|null &$error Custom error message
     * @return bool True jika valid, false jika tidak
     */
    public function password_not_username(
        string $password,
        ?string $fields = null,
        array $data = [],
        &$error = null
    ): bool {
        if (empty($fields) || !isset($data[$fields])) {
            return true; // Skip jika field username tidak ada
        }

        $username = $data[$fields];

        // Cek password tidak mengandung username (case insensitive)
        if (stripos($password, $username) !== false) {
            $error = 'Password tidak boleh mengandung username';
            return false;
        }

        return true;
    }

    /**
     * Validasi password tidak sama dengan password default
     *
     * @param string $password Password yang akan divalidasi
     * @param string|null $fields Tidak digunakan
     * @param array<string, string>|string[] $data Tidak digunakan
     * @param bool|float|int|string|null &$error Custom error message
     * @return bool True jika valid, false jika tidak
     */
    public function not_common_password(
        string $password,
        ?string $fields = null,
        array $data = [],
        &$error = null
    ): bool {
        // Daftar password umum yang harus dihindari
        $commonPasswords = [
            'password', 'Password123', 'admin123', 'qwerty123',
            '12345678', 'Abc12345', 'Admin@123', 'Password@123',
        ];

        if (in_array(strtolower($password), array_map('strtolower', $commonPasswords), true)) {
            $error = 'Password terlalu umum, gunakan password lain';
            return false;
        }

        return true;
    }
}
