<?php

declare(strict_types=1);

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAntrianLogTable extends Migration
{
    public function up(): void
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INTEGER',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'poli_id' => [
                'type' => 'INTEGER',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'nomor' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
            ],
            'status' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'comment' => 'Status akhir antrian',
            ],
            'tanggal' => [
                'type' => 'DATE',
                'comment' => 'Tanggal antrian (untuk reset harian)',
            ],
            'waktu_ambil' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'waktu_panggil' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'waktu_selesai' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('poli_id');
        $this->forge->addKey('tanggal');
        $this->forge->addForeignKey('poli_id', 'poli', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('antrian_log');
    }

    public function down(): void
    {
        $this->forge->dropTable('antrian_log');
    }
}
