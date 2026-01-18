# Panduan Deploy ke aaPanel (CodeIgniter 4)

Berikut adalah langkah-langkah untuk men-deploy aplikasi ini ke aaPanel setelah Anda melakukan `git clone` di folder `wwwroot`.

## 1. Instalasi Dependencies

Masuk ke terminal aaPanel atau SSH, lalu masuk ke folder project:

```bash
cd /www/wwwroot/folder-project-anda
composer install --no-dev --optimize-autoloader
```

> **Catatan:** Pastikan PHP CLI versi 8.1 atau lebih baru sudah terinstall.

## 2. Konfigurasi Environment (.env)

CodeIgniter butuh file konfigurasi `.env`.

1.  Salin file `env` menjadi `.env`:
    ```bash
    cp env .env
    ```
2.  Edit file `.env`:
    ```bash
    nano .env
    ```
3.  Ubah bagian berikut:
    ```ini
    CI_ENVIRONMENT = production
    app.baseURL = 'https://domain-anda.com/'
    ```
    *(Ganti domain-anda.com dengan domain asli)*

## 3. Konfigurasi Database (SQLite)

Aplikasi ini menggunakan SQLite.

1.  Pastikan file `database.sqlite` ada di project root (atau pindahkan ke dalam folder `writable/` agar lebih aman, tapi pastikan konfigurasi di `.env` disesuaikan).
2.  **PENTING:** Berikan hak akses **WRITE** ke file database DAN folder tempat database berada agar aplikasi bisa menyimpan data.

```bash
chown -R www:www /www/wwwroot/folder-project-anda
chmod -R 755 /www/wwwroot/folder-project-anda
chmod -R 777 /www/wwwroot/folder-project-anda/writable
```

## 4. Konfigurasi Website di aaPanel

1.  Buka menu **Website** -> **Add site** (atau edit site yang sudah ada).
2.  **Site Directory**: Arahkan ke `/www/wwwroot/folder-project-anda`.
3.  **Running Directory (PENTING)**: Pilih `/public`.
    *   CodeIgniter 4 **WAJIB** diarahkan ke folder `public` demi keamanan. Jangan arahkan ke root project!
4.  **URL Rewrite (Nginx)**:
    *   Klik setting website -> **URL Rewrite**.
    *   Pilih template **codeigniter** dari dropdown, ATAU copy-paste ini:
    ```nginx
    location / {
        try_files $uri $uri/ /index.php?$args;
    }
    ```

## 5. Setup Audio (TTS Manual)

Karena Anda baru saja men-generate file audio MP3 (Sepuluh, Sebelas, dll) di komputer lokal/dev, Anda perlu mengunggahnya ke server.

1.  Pastikan folder `public/voice` di server berisi semua folder (`numbers`, `letters`, dll).
2.  Jika Anda generate di lokal, **Upload folder `public/voice`** dari komputer Anda ke server (`/www/wwwroot/folder-project-anda/public/voice`).
3.  Pastikan permission folder tersebut bisa dibaca:
    ```bash
    chmod -R 755 /www/wwwroot/folder-project-anda/public/voice
    ```

## 6. Testing

1.  Buka domain Anda di browser.
2.  Login ke panel admin (Default user jika ada).
3.  Cek halaman Kiosk/Display untuk memastikan audio berjalan.

---

### Troubleshooting Umum

*   **Error 500 / Blank Page**:
    *   Cek permission folder `writable`. Folder ini harus bisa ditulisi (logs, cache, session).
    *   Cek log error di `writable/logs/log-tanggah-hari-ini.log`.
*   **Database Locked**:
    *   Biasanya karena permission folder tempat `database.sqlite` berada kurang. Pastikan user `www` (user Nginx/Apache) punya akses write ke file `.sqlite` **DAN** folder induknya.
