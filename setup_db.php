<?php
/**
 * Manual Database Setup Script
 * Run this to create all tables and seed initial data
 */

$dbPath = __DIR__ . '\writable\data\puskesmas.db';

// Remove existing database if any
if (file_exists($dbPath)) {
    echo "Removing existing database...\n";
    unlink($dbPath);
}

// Ensure directory exists
$dir = dirname($dbPath);
if (!is_dir($dir)) {
    mkdir($dir, 0755, true);
}

try {
    $pdo = new PDO('sqlite:' . $dbPath);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "Database created at: $dbPath\n\n";

    // Create poli table
    echo "Creating poli table...\n";
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS poli (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            nama VARCHAR(100) NOT NULL,
            kode VARCHAR(20) NOT NULL,
            prefix VARCHAR(5) NOT NULL,
            urutan INTEGER DEFAULT 0,
            aktif INTEGER DEFAULT 1,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )
    ");

    // Create users table
    echo "Creating users table...\n";
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS users (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            username VARCHAR(50) UNIQUE NOT NULL,
            password VARCHAR(255) NOT NULL,
            nama_lengkap VARCHAR(100),
            email VARCHAR(100),
            role VARCHAR(20) DEFAULT 'petugas',
            aktif INTEGER DEFAULT 1,
            last_login DATETIME,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )
    ");

    // Create user_poli table
    echo "Creating user_poli table...\n";
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS user_poli (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            user_id INTEGER NOT NULL,
            poli_id INTEGER NOT NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
            FOREIGN KEY (poli_id) REFERENCES poli(id) ON DELETE CASCADE
        )
    ");

    // Create antrian table
    echo "Creating antrian table...\n";
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS antrian (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            poli_id INTEGER NOT NULL,
            nomor VARCHAR(20) NOT NULL,
            status VARCHAR(20) DEFAULT 'waiting',
            nama_pasien VARCHAR(100),
            waktu_ambil DATETIME DEFAULT CURRENT_TIMESTAMP,
            waktu_panggil DATETIME,
            waktu_selesai DATETIME,
            dipanggil_oleh INTEGER,
            selesai_oleh INTEGER,
            recall_count INTEGER DEFAULT 0,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (poli_id) REFERENCES poli(id) ON DELETE CASCADE
        )
    ");

    // Create settings table
    echo "Creating settings table...\n";
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS settings (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            key VARCHAR(100) UNIQUE NOT NULL,
            value TEXT,
            description TEXT,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )
    ");

    // Create antrian_log table
    echo "Creating antrian_log table...\n";
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS antrian_log (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            poli_id INTEGER NOT NULL,
            nomor VARCHAR(20) NOT NULL,
            status VARCHAR(20),
            action VARCHAR(50),
            user_id INTEGER,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (poli_id) REFERENCES poli(id) ON DELETE CASCADE
        )
    ");

    echo "\nSeeding data...\n\n";

    // Insert poli data
    echo "Seeding poli data...\n";
    $polis = [
        ['Poli Umum', 'UMUM', 'A', 1],
        ['Poli Gigi', 'GIGI', 'B', 2],
        ['Poli Anak', 'ANAK', 'C', 3],
    ];

    $stmt = $pdo->prepare("INSERT INTO poli (nama, kode, prefix, urutan) VALUES (?, ?, ?, ?)");
    foreach ($polis as $poli) {
        $stmt->execute($poli);
    }

    // Insert admin user
    echo "Seeding admin user...\n";
    $adminPassword = password_hash('admin123', PASSWORD_BCRYPT);
    $stmt = $pdo->prepare("INSERT INTO users (username, password, nama_lengkap, role) VALUES (?, ?, ?, ?)");
    $stmt->execute(['admin', $adminPassword, 'Administrator', 'admin']);

    // Insert settings
    echo "Seeding settings...\n";
    $settings = [
        ['voice_enabled', '1', 'Aktifkan suara panggilan'],
        ['voice_volume', '1.0', 'Volume suara (0-1)'],
        ['recall_max', '3', 'Maksimal panggilan ulang'],
        ['display_count', '5', 'Jumlah antrian ditampilkan'],
        ['auto_refresh_interval', '5', 'Interval auto-refresh (detik)'],
        ['kiosk_show_name', '0', 'Tampilkan input nama di kiosk'],
    ];

    $stmt = $pdo->prepare("INSERT INTO settings (key, value, description) VALUES (?, ?, ?)");
    foreach ($settings as $setting) {
        $stmt->execute($setting);
    }

    echo "\n✅ Database setup complete!\n";
    echo "\nTables created:\n";
    $tables = $pdo->query("SELECT name FROM sqlite_master WHERE type='table' ORDER BY name")->fetchAll(PDO::FETCH_COLUMN);
    foreach ($tables as $table) {
        echo "  - $table\n";
    }

    echo "\nDefault login:\n";
    echo "  Username: admin\n";
    echo "  Password: admin123\n\n";

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
    exit(1);
}
