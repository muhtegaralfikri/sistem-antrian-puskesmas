<?php

declare(strict_types=1);

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            [
                'setting_key' => 'voice_enabled',
                'setting_value' => '1',
                'description' => 'Enable/disable voice call (1 = enabled, 0 = disabled)',
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'setting_key' => 'voice_volume',
                'setting_value' => '0.8',
                'description' => 'Volume suara (0.0 - 1.0)',
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'setting_key' => 'reset_time',
                'setting_value' => '00:00',
                'description' => 'Waktu auto-reset antrian harian (format: HH:MM)',
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'setting_key' => 'display_count',
                'setting_value' => '5',
                'description' => 'Jumlah antrian yang ditampilkan di display',
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'setting_key' => 'kiosk_show_name',
                'setting_value' => '0',
                'description' => 'Tampilkan input nama pasien di kiosk (1 = yes, 0 = no)',
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'setting_key' => 'auto_refresh_interval',
                'setting_value' => '5',
                'description' => 'Interval auto-refresh display dalam detik (fallback jika websocket gagal)',
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'setting_key' => 'recall_max',
                'setting_value' => '3',
                'description' => 'Maksimal jumlah pemanggilan ulang',
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('settings')->insertBatch($data);
    }
}
