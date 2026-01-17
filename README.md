# Sistem Antrian Puskesmas

Sistem antrian digital untuk puskesmas/klinik dengan fitur real-time updates menggunakan WebSocket.

> **💰 Ready untuk dijual!** Sistem ini cross-platform (Windows, Linux, Mac) dan siap pakai audio file pre-recorded.

## Fitur

- 🎫 **Kiosk Mandiri** - Pengambilan tiket antrian tanpa perlu antri di loket
- 📺 **Display Monitor** - Tampilan antrian real-time untuk ruang tunggu
- 👨‍⚕️ **Dashboard Petugas** - Panggil dan kelola antrian dengan mudah
- ⚙️ **Panel Admin** - Kelola poli, pengguna, pengaturan, dan kelola nomor antrian
- 🔊 **Panggilan Suara** - Menggunakan audio file pre-recorded (cross-platform)
- 📊 **Laporan** - Laporan harian dan bulanan untuk monitoring
- 🔄 **Real-time Updates** - WebSocket untuk update langsung tanpa refresh
- 🌍 **Cross-Platform** - Jalan di Windows, Linux, Mac

## Teknologi

- **Backend:** CodeIgniter 4.6.3
- **Database:** SQLite3
- **WebSocket:** Ratchet WebSocket
- **Frontend:** Alpine.js + Tailwind CSS
- **Audio:** MP3 files (pre-recorded) + Fallback TTS browser

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

## 🔊 Setup Audio File

Sistem menggunakan **audio file pre-recorded** untuk pengumuman suara. Lihat panduan lengkap di [AUDIO_SETUP.md](AUDIO_SETUP.md).

### Quick Setup Audio

Buat folder `public/voice/` dengan struktur:
```
voice/
├── numbers/    # 0.mp3, 1.mp3, ..., 100.mp3
├── letters/     # A.mp3, B.mp3, ..., Z.mp3
├── words/       # nomor-antrian.mp3, silakan.mp3, ke.mp3, poli.mp3
└── poli/        # umum.mp3, gigi.mp3, anak.mp3
```

**Tips:** Gunakan layanan TTS online gratis untuk generate audio:
- https://www.soundoftext.com/ (pilih bahasa Indonesia)
- Download hasil dan rename sesuai naming convention

**Fallback:** Jika audio file tidak ada, sistem otomatis menggunakan browser TTS.

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
