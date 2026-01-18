<?php

declare(strict_types=1);

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Migration untuk menambahkan index yang berguna untuk performa query
 */
class AddDatabaseIndexes extends Migration
{
    public function up()
    {
        $this->addAntrianIndexes();
        $this->addUserIndexes();
        $this->addPoliIndexes();
        $this->addUserPoliIndexes();
        $this->addAntrianLogIndexes();
    }

    public function down()
    {
        // Tidak perlu drop index karena akan terhapus bersama table
    }

    /**
     * Index untuk tabel antrian
     * Query yang sering digunakan:
     * - WHERE poli_id = ? AND status = ?
     * - WHERE poli_id = ? ORDER BY id ASC
     * - WHERE DATE(created_at) = ?
     */
    private function addAntrianIndexes(): void
    {
        $this->db->query('CREATE INDEX IF NOT EXISTS idx_antrian_poli_status ON antrian(poli_id, status)');
        $this->db->query('CREATE INDEX IF NOT EXISTS idx_antrian_poli_created ON antrian(poli_id, created_at)');
        $this->db->query('CREATE INDEX IF NOT EXISTS idx_antrian_status_created ON antrian(status, created_at)');
        $this->db->query('CREATE INDEX IF NOT EXISTS idx_antrian_waktu_selesai ON antrian(waktu_selesai)');
    }

    /**
     * Index untuk tabel users
     * Query yang sering digunakan:
     * - WHERE username = ?
     * - WHERE role = ?
     */
    private function addUserIndexes(): void
    {
        $this->db->query('CREATE UNIQUE INDEX IF NOT EXISTS idx_users_username ON users(username)');
        $this->db->query('CREATE INDEX IF NOT EXISTS idx_users_role ON users(role)');
        $this->db->query('CREATE INDEX IF NOT EXISTS idx_users_aktif ON users(aktif)');
    }

    /**
     * Index untuk tabel poli
     * Query yang sering digunakan:
     * - WHERE aktif = ?
     */
    private function addPoliIndexes(): void
    {
        $this->db->query('CREATE INDEX IF NOT EXISTS idx_poli_aktif ON poli(aktif)');
        $this->db->query('CREATE INDEX IF NOT EXISTS idx_poli_prefix ON poli(prefix)');
    }

    /**
     * Index untuk tabel user_poli
     * Query yang sering digunakan:
     * - WHERE user_id = ?
     * - WHERE poli_id = ?
     */
    private function addUserPoliIndexes(): void
    {
        $this->db->query('CREATE INDEX IF NOT EXISTS idx_user_poli_user ON user_poli(user_id)');
        $this->db->query('CREATE INDEX IF NOT EXISTS idx_user_poli_poli ON user_poli(poli_id)');
    }

    /**
     * Index untuk tabel antrian_log
     * Query yang sering digunakan:
     * - WHERE poli_id = ?
     * - WHERE action = ?
     */
    private function addAntrianLogIndexes(): void
    {
        // Index yang sesuai dengan struktur tabel yang ada
        $this->db->query('CREATE INDEX IF NOT EXISTS idx_antrian_log_poli ON antrian_log(poli_id)');
        $this->db->query('CREATE INDEX IF NOT EXISTS idx_antrian_log_action ON antrian_log(action)');
        $this->db->query('CREATE INDEX IF NOT EXISTS idx_antrian_log_user ON antrian_log(user_id)');
        $this->db->query('CREATE INDEX IF NOT EXISTS idx_antrian_log_created ON antrian_log(created_at)');
    }
}
