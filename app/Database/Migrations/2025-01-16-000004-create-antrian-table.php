<?php

declare(strict_types=1);

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAntrianTable extends Migration
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
                'comment' => 'Format: A-001, B-001, dll',
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['waiting', 'called', 'serving', 'completed', 'skipped'],
                'default' => 'waiting',
            ],
            'nama_pasien' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
                'comment' => 'Opsional: nama pasien jika diisi',
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
            'dipanggil_oleh' => [
                'type' => 'INTEGER',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
                'comment' => 'user_id yang memanggil',
            ],
            'selesai_oleh' => [
                'type' => 'INTEGER',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
                'comment' => 'user_id yang menyelesaikan',
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('poli_id');
        $this->forge->addKey('status');
        $this->forge->addKey('nomor');
        $this->forge->addForeignKey('poli_id', 'poli', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('dipanggil_oleh', 'users', 'id', 'SET NULL', 'SET NULL');
        $this->forge->addForeignKey('selesai_oleh', 'users', 'id', 'SET NULL', 'SET NULL');
        $this->forge->createTable('antrian');
    }

    public function down(): void
    {
        $this->forge->dropTable('antrian');
    }
}
