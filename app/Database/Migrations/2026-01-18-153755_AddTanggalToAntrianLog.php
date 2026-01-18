<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddTanggalToAntrianLog extends Migration
{
    public function up()
    {
        // Check if column exists to avoid errors
        if (!$this->db->fieldExists('tanggal', 'antrian_log')) {
            $fields = [
                'tanggal' => [
                    'type' => 'DATE',
                    'null' => true,
                    'after' => 'status',
                ],
            ];
            $this->forge->addColumn('antrian_log', $fields);
            
            // Add index for performance
            $this->db->query('ALTER TABLE antrian_log ADD INDEX (tanggal)');
        }
    }

    public function down()
    {
        if ($this->db->fieldExists('tanggal', 'antrian_log')) {
            $this->forge->dropColumn('antrian_log', 'tanggal');
        }
    }
}
