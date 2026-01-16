<?php

declare(strict_types=1);

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class PoliSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            [
                'nama' => 'Poli Umum',
                'kode' => 'UMUM',
                'prefix' => 'A',
                'aktif' => 1,
                'urutan' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'nama' => 'Poli Gigi',
                'kode' => 'GIGI',
                'prefix' => 'B',
                'aktif' => 1,
                'urutan' => 2,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'nama' => 'Poli Anak',
                'kode' => 'ANAK',
                'prefix' => 'C',
                'aktif' => 1,
                'urutan' => 3,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('poli')->insertBatch($data);
    }
}
