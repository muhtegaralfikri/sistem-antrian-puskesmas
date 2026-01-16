<?php

declare(strict_types=1);

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePoliTable extends Migration
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
            'nama' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'kode' => [
                'type' => 'VARCHAR',
                'constraint' => 10,
            ],
            'prefix' => [
                'type' => 'VARCHAR',
                'constraint' => 10,
                'comment' => 'Prefix untuk nomor antrian (A, B, C, dll)',
            ],
            'aktif' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 1,
                'comment' => '1 = aktif, 0 = non-aktif',
            ],
            'urutan' => [
                'type' => 'INTEGER',
                'constraint' => 11,
                'default' => 0,
                'comment' => 'Urutan tampilan',
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
        $this->forge->addUniqueKey('kode');
        $this->forge->createTable('poli');
    }

    public function down(): void
    {
        $this->forge->dropTable('poli');
    }
}
