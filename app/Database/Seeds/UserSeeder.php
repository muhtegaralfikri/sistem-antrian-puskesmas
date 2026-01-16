<?php

declare(strict_types=1);

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Password: admin123
        $adminPassword = password_hash('admin123', PASSWORD_DEFAULT);

        $data = [
            [
                'username' => 'admin',
                'password' => $adminPassword,
                'nama_lengkap' => 'Administrator',
                'email' => 'admin@puskesmas.local',
                'role' => 'admin',
                'aktif' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('users')->insertBatch($data);

        // Assign admin to all polis
        $adminId = $this->db->insertID();
        $polis = $this->db->table('poli')->get()->getResultArray();

        $userPoliData = [];
        foreach ($polis as $poli) {
            $userPoliData[] = [
                'user_id' => $adminId,
                'poli_id' => $poli['id'],
                'created_at' => date('Y-m-d H:i:s'),
            ];
        }

        if (!empty($userPoliData)) {
            $this->db->table('user_poli')->insertBatch($userPoliData);
        }
    }
}
