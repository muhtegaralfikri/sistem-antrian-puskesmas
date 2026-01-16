# Sistem Antrian Puskesmas

Sistem antrian digital untuk puskesmas/klinik dengan fitur real-time updates menggunakan WebSocket.

## Fitur

- 🎫 **Kiosk Mandiri** - Pengambilan tiket antrian tanpa perlu antri di loket
- 📺 **Display Monitor** - Tampilan antrian real-time untuk ruang tunggu
- 👨‍⚕️ **Dashboard Petugas** - Panggil dan kelola antrian dengan mudah
- ⚙️ **Panel Admin** - Kelola poli, pengguna, dan pengaturan sistem
- 🔊 **Panggilan Suara** - Notifikasi suara untuk nomor antrian yang dipanggil
- 📊 **Laporan** - Laporan harian dan bulanan untuk monitoring
- 🔄 **Real-time Updates** - WebSocket untuk update langsung tanpa refresh

## Teknologi

- **Backend:** CodeIgniter 4.6.3
- **Database:** SQLite3
- **WebSocket:** Ratchet WebSocket
- **Frontend:** Alpine.js + Tailwind CSS

## Persyaratan Server

- PHP 8.1 atau higher
- Ekstensi PHP: `intl`, `mbstring`, `sqlite3`, `json`
- Composer (untuk install dependencies)

## Instalasi

### 1. Clone Repository

```bash
git clone https://github.com/muhtegaralfikri/sistem-antrian-puskesmas.git
cd sistem-antrian-puskesmas
```

### 2. Install Dependencies

```bash
composer install
```

### 3. Setup Database

```bash
php setup_db.php
```

Script ini akan membuat database SQLite dan menambahkan data awal:
- 3 Poli (Umum, Gigi, Anak)
- 1 User Admin
- Pengaturan default

### 4. Konfigurasi

Copy `env` ke `.env` dan sesuaikan:

```bash
cp env .env
```

Edit `.env` untuk mengatur baseURL:

```ini
app.baseURL = 'http://localhost:8000'
```

### 5. Jalankan Server

**Windows:**
```bash
start.bat
```

**Linux/Mac:**
```bash
php spark serve
```

Server akan berjalan di `http://localhost:8000`

## Penggunaan

### URL yang Tersedia

| Halaman | URL | Deskripsi |
|---------|-----|-----------|
| Kiosk | `/kiosk` | Ambil tiket antrian |
| Display | `/display` | Monitor antrian |
| Dashboard | `/dashboard` | Panel petugas |
| Admin | `/admin` | Panel admin |
| Login | `/auth/login` | Halaman login |

### Login Default

```
Username: admin
Password: admin123
```

### Jalankan WebSocket Server

Untuk real-time updates, jalankan WebSocket server di terminal terpisah:

```bash
php spark websocket:start
```

WebSocket akan berjalan di port `8080`

## Struktur Database

```
┌─────────────────┐
│     poli        │  ◄── Data poli/layanan
├─────────────────┤
│     users       │  ◄── Data petugas & admin
├─────────────────┤
│   user_poli     │  ◄── Relasi user & poli
├─────────────────┤
│    antrian      │  ◄── Data antrian
├─────────────────┤
│   settings      │  ◄── Pengaturan sistem
├─────────────────┤
│  antrian_log    │  ◄── Log aktivitas antrian
└─────────────────┘
```

## Pengaturan Sistem

Berikut pengaturan yang dapat diubah melalui panel admin:

| Pengaturan | Default | Deskripsi |
|-----------|---------|-----------|
| voice_enabled | 1 | Aktifkan suara panggilan |
| voice_volume | 1.0 | Volume suara (0-1) |
| recall_max | 3 | Maksimal panggilan ulang |
| display_count | 5 | Jumlah antrian ditampilkan |
| auto_refresh_interval | 5 | Interval auto-refresh (detik) |
| kiosk_show_name | 0 | Tampilkan input nama di kiosk |

## License

MIT License

## Credits

Dibuat dengan ❤️ menggunakan CodeIgniter 4
