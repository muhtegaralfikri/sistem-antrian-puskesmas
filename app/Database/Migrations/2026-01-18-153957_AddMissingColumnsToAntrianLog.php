<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddMissingColumnsToAntrianLog extends Migration
{
    public function up()
    {
        $fields = [];

        if (!$this->db->fieldExists('waktu_ambil', 'antrian_log')) {
            $fields['waktu_ambil'] = [
                'type' => 'DATETIME',
                'null' => true,
                'after' => 'tanggal',
            ];
        }

        if (!$this->db->fieldExists('waktu_panggil', 'antrian_log')) {
            $fields['waktu_panggil'] = [
                'type' => 'DATETIME',
                'null' => true,
                'after' => 'waktu_ambil',
            ];
        }

        if (!$this->db->fieldExists('waktu_selesai', 'antrian_log')) {
            $fields['waktu_selesai'] = [
                'type' => 'DATETIME',
                'null' => true,
                'after' => 'waktu_panggil',
            ];
        }

        if (!empty($fields)) {
            $this->forge->addColumn('antrian_log', $fields);
        }
    }

    public function down()
    {
        $fields = [];
        if ($this->db->fieldExists('waktu_ambil', 'antrian_log')) {
            $fields[] = 'waktu_ambil';
        }
        if ($this->db->fieldExists('waktu_panggil', 'antrian_log')) {
            $fields[] = 'waktu_panggil';
        }
        if ($this->db->fieldExists('waktu_selesai', 'antrian_log')) {
            $fields[] = 'waktu_selesai';
        }

        if (!empty($fields)) {
            $this->forge->dropColumn('antrian_log', $fields);
        }
    }
}
