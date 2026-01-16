<?php

declare(strict_types=1);

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUserPoliTable extends Migration
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
            'user_id' => [
                'type' => 'INTEGER',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'poli_id' => [
                'type' => 'INTEGER',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('poli_id', 'poli', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addUniqueKey(['user_id', 'poli_id']);
        $this->forge->createTable('user_poli');
    }

    public function down(): void
    {
        $this->forge->dropTable('user_poli');
    }
}
