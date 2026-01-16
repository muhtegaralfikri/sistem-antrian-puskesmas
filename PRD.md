# PRD - Sistem Antrian Puskesmas
## Product Requirements Document

**Version:** 1.0
**Date:** 2025-01-16
**Status:** Draft
**Author:** Claude Code

---

## 1. Project Overview

### 1.1 Deskripsi
Sistem antrian digital untuk puskesmas dengan fitur:
- Pengambilan nomor antrian tiket
- Pemanggilan antrian dengan suara (voice)
- Display publik untuk menampilkan antrian aktif
- Manajemen multi-poliklinik (poli)
- Dashboard admin untuk petugas
- Sistem autentikasi dan role management

### 1.2 Tujuan
- Mengurangi antrean manual yang berantakan
- Meningkatkan kenyamanan pasien
- Memudahkan petugas memanggil pasien
- Mencatat history antrian untuk statistik

### 1.3 Scope in Scope
| Fitur | Deskripsi |
|-------|-----------|
| Kiosk Tiket | Pasien ambil nomor antrian & print tiket |
| Display Publik | TV/monitor yang menampilkan antrian aktif |
| Dashboard Petugas | Panggil antrian, lihat status, reset |
| Multi Poli | Umum, Gigi, dan bisa tambah poli lain |
| Voice Call | Suara "Nomor A-5 Poli Umum Dipersilahkan" |
| Auth Login | Login untuk admin dan petugas |
| History & Statistik | Laporan harian/bulanan |
| Real-time Sync | Semua display update otomatis |

### 1.4 Scope Out of Scope
- Booking antrian online
- Integrasi dengan sistem lain (BPJS, SATUSEHAT)
- Aplikasi mobile pasien
- Notifikasi WhatsApp/SMS
- Multi-branch/cabang

---

## 2. User Personas

| Role | Deskripsi | Kebutuhan |
|------|-----------|-----------|
| **Pasien** | Orang yang berobat di puskesmas | Ambil nomor, lihat antrian, dengar panggilan |
| **Petugas Poli** | Perawat/admin yang memanggil pasien | Panggil nomor, lihat daftar antrian, selesaikan antrian |
| **Admin** | Manager sistem | Kelola poli, kelola user, lihat laporan, reset sistem |

---

## 3. User Stories

### 3.1 Sebagai Pasien
```
US-P01: Saya ingin mengambil nomor antrian untuk poli tertentu
US-P02: Saya ingin mendapatkan tiket cetak yang berisi nomor antrian saya
US-P03: Saya ingin melihat nomor antrian saya di display publik
US-P04: Saya ingin mendengar panggilan nomor antrian saya
US-P05: Saya ingin melihat estimasi sisa antrian di depan saya
```

### 3.2 Sebagai Petugas Poli
```
US-PET01: Saya ingin memanggil nomor antrian berikutnya
US-PET02: Saya ingin memanggil ulang nomor antrian (recall)
US-PET03: Saya ingin menandai nomor antrian sudah dilayani/selesai
US-PET04: Saya ingin melihat daftar antrian yang menunggu
US-PET05: Saya ingin melewati (skip) nomor antrian jika pasien tidak ada
```

### 3.3 Sebagai Admin
```
US-ADM01: Saya ingin login ke sistem admin
US-ADM02: Saya ingin menambah/mengedit/menghapus poli
US-ADM03: Saya ingin menambah/mengedit/menghapus user petugas
US-ADM04: Saya ingin melihat laporan harian antrian
US-ADM05: Saya ingin mereset nomor antrian (reset harian)
US-ADM06: Saya ingin mengatur pengaturan sistem (volume suara, dll)
```

---

## 4. Functional Requirements

### 4.1 Modul Kiosk (Pengambilan Tiket)

| ID | Requirement | Priority |
|----|-------------|----------|
| FR-K01 | Tampilan daftar poli yang tersedia | Must Have |
| FR-K02 | Tombol untuk ambil nomor antrian per poli | Must Have |
| FR-K03 | Generate nomor antrian otomatis (A-001, B-001, dll) | Must Have |
| FR-K04 | Cetak tiket berisi: nomor, poli, tanggal, jam ambil | Must Have |
| FR-K05 | Tampilkan estimasi antrian di depan | Should Have |
| FR-K06 | QR Code pada tiket (opsional untuk scan) | Nice to Have |

### 4.2 Modul Display Publik

| ID | Requirement | Priority |
|----|-------------|----------|
| FR-D01 | Tampilkan poli dengan antrian saat ini dilayani | Must Have |
| FR-D02 | Tampilkan nomor antrian yang sedang dipanggil | Must Have |
| FR-D03 | Tampilkan 3-5 nomor antrian sebelumnya | Must Have |
| FR-D04 | Auto-play suara panggilan saat nomor berubah | Must Have |
| FR-D05 | Layout responsive untuk TV/monitor besar | Must Have |
| FR-D06 | Auto-refresh real-time via WebSocket | Must Have |

### 4.3 Modul Dashboard Petugas

| ID | Requirement | Priority |
|----|-------------|----------|
| FR-P01 | Login dengan username & password | Must Have |
| FR-P02 | Filter berdasarkan poli yang ditugaskan | Must Have |
| FR-P03 | Daftar antrian yang menunggu (queue) | Must Have |
| FR-P04 | Tombol "Panggil Berikutnya" | Must Have |
| FR-P05 | Tombol "Panggil Ulang" (recall) | Must Have |
| FR-P06 | Tombol "Selesaikan" (complete) | Must Have |
| FR-P07 | Tombol "Lewati" (skip) | Should Have |
| FR-P08 | Tampilkan antrian sedang dilayani | Must Have |

### 4.4 Modul Admin

| ID | Requirement | Priority |
|----|-------------|----------|
| FR-A01 | CRUD Poli (tambah, edit, hapus) | Must Have |
| FR-A02 | CRUD User (tambah, edit, hapus) | Must Have |
| FR-A03 | Role Management (Admin, Petugas) | Must Have |
| FR-A04 | Dashboard statistik antrian | Must Have |
| FR-A05 | Laporan harian/bulanan | Should Have |
| FR-A06 | Reset nomor antrian (per poli atau semua) | Must Have |
| FR-A07 | Settings (volume suara, dll) | Nice to Have |

### 4.5 Modul Voice (Suara)

| ID | Requirement | Priority |
|----|-------------|----------|
| FR-V01 | Format suara: "Nomor [A-5] [Poli] Dipersilahkan" | Must Have |
| FR-V02 | Support bahasa Indonesia | Must Have |
| FR-V03 | Auto-play saat ada antrian baru dipanggil | Must Have |
| FR-V04 | Volume suara bisa diatur | Should Have |
| FR-V05 | Bisa di-play manual (recall) | Must Have |

---

## 5. Non-Functional Requirements

| Category | Requirement | Target |
|----------|-------------|--------|
| **Performance** | Load time halaman | < 2 detik |
| | Real-time update latency | < 1 detik |
| **Scalability** | Support poli | Up to 10 poli |
| | Support antrian per hari | Up to 500 pasien |
| **Reliability** | Uptime | 99% (dalam jam operasional) |
| **Usability** | Touch-friendly (kiosk) | Large buttons |
| | Readable dari jauh (display) | Font besar |
| **Security** | Password hashing | bcrypt/argon2 |
| | Session timeout | 30 menit idle |
| **Browser Support** | Chrome, Edge, Firefox | Latest 2 versions |

---

## 6. Technical Specifications

### 6.1 Tech Stack

| Layer | Technology |
|-------|------------|
| **Backend Framework** | CodeIgniter 4.x |
| **Database** | SQLite 3 |
| **Real-time** | Ratchet (WebSocket) / Polling fallback |
| **Frontend** | Alpine.js 3.x |
| **CSS** | Tailwind CSS 3.x |
| **Authentication** | CodeIgniter Shield / Custom Session |
| **Web Server** | Nginx + PHP-FPM |
| **PHP Version** | 8.4+ |

### 6.2 Server Requirements

| Requirement | Specification |
|-------------|---------------|
| OS | Ubuntu 22.04 LTS |
| CPU | 2 cores |
| RAM | 1.9 GB |
| Disk | 30 GB |
| PHP | 8.4+ |
| Extension | pdo_sqlite, mbstring, json, curl |

### 6.3 Database Schema

```sql
-- Table: poli
CREATE TABLE poli (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nama VARCHAR(100) NOT NULL,
    kode VARCHAR(10) NOT NULL UNIQUE,  -- A, B, C, dll
    prefix VARCHAR(10) NOT NULL,       -- A, B, C, dll
    aktif BOOLEAN DEFAULT 1,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Table: users
CREATE TABLE users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    nama_lengkap VARCHAR(100) NOT NULL,
    email VARCHAR(100),
    role ENUM('admin', 'petugas') DEFAULT 'petugas',
    aktif BOOLEAN DEFAULT 1,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Table: user_poli (many-to-many: user bisa handle beberapa poli)
CREATE TABLE user_poli (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    user_id INTEGER NOT NULL,
    poli_id INTEGER NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (poli_id) REFERENCES poli(id) ON DELETE CASCADE,
    UNIQUE(user_id, poli_id)
);

-- Table: antrian
CREATE TABLE antrian (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    poli_id INTEGER NOT NULL,
    nomor VARCHAR(20) NOT NULL,  -- A-001, B-001, dst
    status ENUM('waiting', 'called', 'serving', 'completed', 'skipped') DEFAULT 'waiting',
    nama_pasien VARCHAR(100),     -- Opsional: jika pasien isi nama
    waktu_ambil DATETIME DEFAULT CURRENT_TIMESTAMP,
    waktu_panggil DATETIME,
    waktu_selesai DATETIME,
    dipanggil_oleh INTEGER,       -- user_id yang memanggil
    selesai_oleh INTEGER,         -- user_id yang menyelesaikan
    FOREIGN KEY (poli_id) REFERENCES poli(id),
    FOREIGN KEY (dipanggil_oleh) REFERENCES users(id),
    FOREIGN KEY (selesai_oleh) REFERENCES users(id)
);

-- Table: antrian_log (untuk history dan reset)
CREATE TABLE antrian_log (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    poli_id INTEGER NOT NULL,
    nomor VARCHAR(20) NOT NULL,
    status VARCHAR(20) NOT NULL,
    tanggal DATE NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (poli_id) REFERENCES poli(id)
);

-- Table: settings (pengaturan sistem)
CREATE TABLE settings (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    key VARCHAR(50) NOT NULL UNIQUE,
    value TEXT,
    description VARCHAR(255),
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
);
```

### 6.4 Sample Data

```sql
-- Poli Initial Data
INSERT INTO poli (nama, kode, prefix) VALUES
('Poli Umum', 'UMUM', 'A'),
('Poli Gigi', 'GIGI', 'B');

-- Admin Default (password: admin123)
INSERT INTO users (username, password, nama_lengkap, role) VALUES
('admin', '$2y$10$xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx', 'Administrator', 'admin');

-- Petugas Sample
INSERT INTO users (username, password, nama_lengkap, role) VALUES
('petugas1', '$2y$10$xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx', 'Perawat A', 'petugas'),
('petugas2', '$2y$10$xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx', 'Perawat B', 'petugas');

-- Settings Initial Data
INSERT INTO settings (key, value, description) VALUES
('voice_enabled', '1', 'Enable/disable voice call'),
('voice_volume', '0.8', 'Volume suara (0.0 - 1.0)'),
('reset_time', '00:00', 'Waktu auto-reset antrian harian'),
('display_count', '5', 'Jumlah antrian yang ditampilkan di display');
```

---

## 7. API Endpoints

### 7.1 Public Endpoints (Tanpa Auth)

| Method | Endpoint | Deskripsi |
|--------|----------|-----------|
| GET | /api/display | Data untuk display publik |
| GET | /api/poli | List semua poli aktif |
| POST | /api/antrian/ambil | Ambil nomor antrian baru (kiosk) |
| GET | /api/antrian/tiket/{id} | Detail tiket untuk print |

### 7.2 Auth Required (Petugas/Admin)

| Method | Endpoint | Deskripsi |
|--------|----------|-----------|
| POST | /api/auth/login | Login petugas/admin |
| GET | /api/auth/me | Get current user info |
| POST | /api/auth/logout | Logout |
| GET | /api/dashboard | Dashboard data (filter by user's poli) |
| GET | /api/antrian/queue/{poli_id} | List antrian menunggu |
| POST | /api/antrian/panggil | Panggil antrian berikutnya |
| POST | /api/antrian/recall/{id} | Panggil ulang |
| POST | /api/antrian/selesai/{id} | Tandai selesai |
| POST | /api/antrian/skip/{id} | Lewati antrian |

### 7.3 Admin Only

| Method | Endpoint | Deskripsi |
|--------|----------|-----------|
| GET | /api/admin/poli | List semua poli |
| POST | /api/admin/poli | Tambah poli baru |
| PUT | /api/admin/poli/{id} | Update poli |
| DELETE | /api/admin/poli/{id} | Hapus poli |
| GET | /api/admin/users | List semua users |
| POST | /api/admin/users | Tambah user baru |
| PUT | /api/admin/users/{id} | Update user |
| DELETE | /api/admin/users/{id} | Hapus user |
| POST | /api/admin/reset-antrian/{poli_id} | Reset antrian per poli |
| POST | /api/admin/reset-all | Reset semua antrian |
| GET | /api/admin/laporan/harian | Laporan harian |
| GET | /api/admin/laporan/bulanan | Laporan bulanan |
| GET | /api/admin/settings | Get semua settings |
| PUT | /api/admin/settings | Update settings |

---

## 8. WebSocket Events

### 8.1 Server → Client

| Event | Payload | Deskripsi |
|-------|---------|-----------|
| `antrian:baru` | `{poli_id, nomor, antrian_id}` | Antrian baru diambil |
| `antrian:panggil` | `{poli_id, nomor, antrian_id}` | Antrian dipanggil |
| `antrian:selesai` | `{poli_id, nomor, antrian_id}` | Antrian selesai |
| `antrian:skip` | `{poli_id, nomor, antrian_id}` | Antrian dilewati |
| `display:update` | `{data}` | Full display data update |

### 8.2 Client → Server

| Event | Payload | Deskripsi |
|-------|---------|-----------|
| `display:subscribe` | `{}` | Subscribe ke display updates |

---

## 9. UI/UX Flow

### 9.1 Kiosk Flow

```
[Home Kiosk]
    ↓
[Pilih Poli] → {Poli Umum, Poli Gigi, ...}
    ↓
[Konfirmasi] → "Ambil nomor antrian untuk [Poli]?"
    ↓
[Proses] → Generate nomor → Simpan ke DB
    ↓
[Tampil Tiket] → Show + Print dialog
    ↓
[Selesai] → Kembali ke Home
```

### 9.2 Display Publik Flow

```
[Auto-load]
    ↓
[Subscribe WebSocket] → Connect ke real-time updates
    ↓
[Render Display] → Tampilkan:
    - Poli dengan antrian aktif
    - Nomor yang sedang dilayani
    - 3-5 nomor sebelumnya
    ↓
[On Update] → WebSocket event → Re-render + Play Voice
```

### 9.3 Petugas Flow

```
[Login]
    ↓
[Dashboard] → Filter by poli (jika petugas biasa)
    ↓
[Daftar Antrian] → List antrian waiting
    ↓
[Aksi] → {Panggil | Recall | Selesai | Skip}
    ↓
[Real-time Update] → Semua display terupdate
```

### 9.4 Admin Flow

```
[Login]
    ↓
[Dashboard Admin] →
    - Stats overview
    - Quick actions
    ↓
[Menu] → {Manajemen Poli | Manajemen User | Laporan | Settings}
```

---

## 10. Page Structure

### 10.1 Pages Overview

| Page | Path | Access | Description |
|------|------|--------|-------------|
| Kiosk | `/kiosk` | Public | Ambil & print tiket |
| Display | `/display` | Public | TV display publik |
| Login | `/login` | Public | Login page |
| Dashboard Petugas | `/dashboard` | Petugas+ | Panel petugas |
| Admin Poli | `/admin/poli` | Admin | CRUD poli |
| Admin Users | `/admin/users` | Admin | CRUD users |
| Admin Laporan | `/admin/laporan` | Admin | Laporan |
| Admin Settings | `/admin/settings` | Admin | Settings |

### 10.2 Kiosk Page Wireframe

```
┌─────────────────────────────────────────────┐
│           PUSKESMAS ANTRIAN                 │
├─────────────────────────────────────────────┤
│                                             │
│    Silahkan Pilih Poliklinik:               │
│                                             │
│   ┌─────────────┐  ┌─────────────┐         │
│   │             │  │             │         │
│   │  POLI UMUM  │  │  POLI GIGI  │         │
│   │             │  │             │         │
│   └─────────────┘  └─────────────┘         │
│                                             │
│   ┌─────────────┐  ┌─────────────┐         │
│   │  POLI ANAK  │  │ POLI LANJUT │         │
│   └─────────────┘  └─────────────┘         │
│                                             │
└─────────────────────────────────────────────┘
```

### 10.3 Display Page Wireframe

```
┌────────────────────────────────────────────────────────────┐
│               PUSKESMAS ANTRIAN PUBLIK                     │
├────────────────────────────────────────────────────────────┤
│                                                            │
│  ┌─────────────────┐  ┌─────────────────┐  ┌──────────────┐│
│  │   POLI UMUM     │  │   POLI GIGI     │  │  POLI ANAK   ││
│  │                 │  │                 │  │              ││
│  │     A - 005     │  │     B - 002     │  │    C - 001   ││
│  │  Sedang Dilayani│  │  Sedang Dilayani│  │Dilayani      ││
│  └─────────────────┘  └─────────────────┘  └──────────────┘│
│                                                            │
│  Antrian Sebelumnya:                                       │
│  ┌──────┐  ┌──────┐  ┌──────┐  ┌──────┐  ┌──────┐        │
│  │A-004 │  │A-003 │  │B-001 │  │C-001 │  │      │        │
│  └──────┘  └──────┘  └──────┘  └──────┘  └──────┘        │
│                                                            │
│  ┌────────────────────────────────────────────────────┐   │
│  │  Status: Menunggu | Terakhir Update: 10:30:15     │   │
│  └────────────────────────────────────────────────────┘   │
└────────────────────────────────────────────────────────────┘
```

### 10.4 Petugas Dashboard Wireframe

```
┌────────────────────────────────────────────────────────────┐
│  Dashboard Petugas  |  User: Perawat A  |  [Logout]       │
├────────────────────────────────────────────────────────────┤
│                                                            │
│  Poli: [Poli Umum ▼]                                      │
│                                                            │
│  ┌────────────────────────────────────────────────────┐   │
│  │  SEDANG DILAYANI                                    │   │
│  │                                                     │   │
│  │     Nomor: A - 005                                  │   │
│  │     Status: Sedang Dilayani                        │   │
│  │                                                     │   │
│  │  [Panggil Ulang]  [Selesai]  [Lewati]             │   │
│  └────────────────────────────────────────────────────┘   │
│                                                            │
│  ┌────────────────────────────────────────────────────┐   │
│  │  ANTRIAN MENUNGGU                                   │   │
│  │                                                     │   │
│  │  ┌─────┐ ┌─────┐ ┌─────┐ ┌─────┐                   │   │
│  │  │A-006│ │A-007│ │A-008│ │A-009│  [Panggil Berikutnya] │   │
│  │  └─────┘ └─────┘ └─────┘ └─────┘                   │   │
│  └────────────────────────────────────────────────────┘   │
│                                                            │
│  ┌────────────────────────────────────────────────────┐   │
│  │  RIWAYAT HARI INI                                   │   │
│  │  Total: 10  |  Selesai: 5  |  Menunggu: 5          │   │
│  └────────────────────────────────────────────────────┘   │
└────────────────────────────────────────────────────────────┘
```

---

## 11. Security Considerations

### 11.1 Authentication
- Password hashing menggunakan bcrypt/argon2
- Session timeout setelah 30 menit idle
- CSRF protection untuk form submissions
- SQL Injection prevention via Query Builder/Prepared Statements

### 11.2 Authorization
- Role-based access control (Admin, Petugas)
- Petugas hanya akses poli yang ditugaskan
- Admin full access

### 11.3 Input Validation
- Sanitize semua input user
- Validasi nomor antrian (prevent duplicate)
- Validasi file upload (jika ada)

---

## 12. Deployment Plan

### 12.1 Server Preparation

```bash
# Install dependencies
apt update
apt install nginx php8.4-fpm php8.4-sqlite3 php8.4-mbstring php8.4-xml php8.4-curl composer

# Clone project
cd /var/www
git clone [repository] puskesmas-antrian
cd puskesmas-antrian

# Install PHP dependencies
composer install --no-dev

# Set permissions
chown -R www-data:www-data /var/www/puskesmas-antrian
chmod -R 755 /var/www/puskesmas-antrian

# Configure Nginx
# (create nginx config)

# Start WebSocket server (systemd service)
# (create systemd service file)
```

### 12.2 Nginx Config Sample

```nginx
server {
    listen 80;
    server_name 157.15.124.246;
    root /var/www/puskesmas-antrian/public;

    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.4-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    # WebSocket endpoint
    location /ws {
        proxy_pass http://localhost:8080;
        proxy_http_version 1.1;
        proxy_set_header Upgrade $http_upgrade;
        proxy_set_header Connection "upgrade";
    }
}
```

### 12.3 WebSocket Service (systemd)

```ini
[Unit]
Description=Puskesmas Queue WebSocket Server
After=network.target

[Service]
Type=simple
User=www-data
WorkingDirectory=/var/www/puskesmas-antrian
ExecStart=/usr/bin/php spark websocket:start
Restart=always

[Install]
WantedBy=multi-user.target
```

---

## 13. Testing Plan

### 13.1 Unit Testing
- Model tests (CRUD antrian, poli, users)
- Helper tests (format nomor antrian, etc)

### 13.2 Integration Testing
- API endpoint tests
- WebSocket connection tests

### 13.3 User Acceptance Testing (UAT)
| Scenario | Expected Result |
|----------|-----------------|
| Pasien ambil tiket | Tiket tercetak, nomor muncul di display |
| Petugas panggil antrian | Suara terdengar, display update |
| Petugas selesaikan antrian | Status berubah, antrian berikutnya siap |
| Admin tambah poli | Poli baru muncul di kiosk |
| Admin reset antrian | Nomor mulai dari 001 lagi |

---

## 14. Future Enhancements (Out of Scope v1)

- [ ] Booking antrian online via web
- [ ] Mobile app untuk pasien (lihat antrian dari HP)
- [ ] Notifikasi WhatsApp/SMS saat giliran dekat
- [ ] Integrasi dengan SATUSEHAT/BPJS
- [ ] Multi-branch/cabang synchronization
- [ ] Kiosk dengan layar sentuh
- [ ] Digital signage integration
- [ ] Analytics dashboard yang lebih advanced

---

## 15. Glossary

| Term | Definition |
|------|------------|
| **Poli** | Poliklinik - unit pelayanan medis tertentu |
| **Antrian** | Queue - pasien yang menunggu giliran |
| **Kiosk** | Terminal untuk pasien ambil nomor |
| **Display** | Monitor/TV untuk menampilkan antrian |
| **Tiket** | Bukti nomor antrian (cetak) |
| **Recall** | Memanggil ulang nomor yang sama |
| **Skip** | Melewati nomor antrian |

---

## 16. Approval

| Role | Name | Signature | Date |
|------|------|-----------|------|
| Product Owner | | | |
| Tech Lead | | | |
| Developer | | | |

---

**Document Version History:**

| Version | Date | Author | Changes |
|---------|------|--------|---------|
| 1.0 | 2025-01-16 | Claude Code | Initial PRD |
