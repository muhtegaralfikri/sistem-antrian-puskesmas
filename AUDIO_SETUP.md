# Panduan Setup Audio untuk Sistem Antrian

## Overview

Sistem antrian ini menggunakan **audio file pre-recorded** untuk pengumuman suara. Solusi ini:
- ✅ **Cross-platform** (Windows, Linux, Mac)
- ✅ **Offline ready** (tidak butuh internet)
- ✅ **Kualitas profesional** (suara asli rekaman)
- ✅ **Fallback otomatis** ke TTS browser jika audio file tidak ada

---

## Struktur Folder Audio

```
public/voice/
├── numbers/          # Angka 0-100
│   ├── 0.mp3         # "Nol"
│   ├── 1.mp3         # "Satu"
│   ├── 2.mp3         # "Dua"
│   └── ...
├── letters/          # Huruf A-Z
│   ├── A.mp3         # "A"
│   ├── B.mp3         # "B"
│   └── ...
├── words/            # Kata-kata umum
│   ├── nomor-antrian.mp3   # "Nomor antrian"
│   ├── silakan.mp3         # "Silakan"
│   ├── ke.mp3              # "Ke"
│   └── poli.mp3            # "Poli"
└── poli/             # Nama poli (dinamis)
    ├── umum.mp3            # "Umum"
    ├── gigi.mp3            # "Gigi"
    └── anak.mp3            # "Anak"
```

---

## Cara Membuat Audio Files

### Opsi 1: Online TTS (Gratis - Untuk Testing)

Kunjungi salah satu layanan TTS Indonesia gratis:
- **https://www.soundoftext.com/** (Pilih bahasa Indonesia)
- **https://ttsmp3.com/** (Indonesian available)

Setelah dapat audio:
1. Download file MP3
2. Rename sesuai naming convention
3. Letakkan di folder yang sesuai

### Opsi 2: Windows Voice Recorder (Gratis - Hasil Bagus)

```powershell
# Buka Voice Recorder app di Windows
# Rekam setiap kata/angka dengan jelas
```

### Opsi 3: Hire Voice Talent (Professional - Untuk Produksi)

Untuk produk jual, disarankan hire voice talent profesional. Harga sekitar Rp 500.000 - 2.000.000 untuk full pack.

---

## File yang Dibutuhkan

### Minimal (Testing)

Untuk testing, buat file-file berikut:

**Numbers (0-9) - DIBACA PER DIGIT:**
```
voice/numbers/0.mp3  → "Nol"
voice/numbers/1.mp3  → "Satu"
voice/numbers/2.mp3  → "Dua"
...
voice/numbers/9.mp3  → "Sembilan"
```

**Contoh:**
- C-001 → dibaca "C Nol Nol Satu"
- A-015 → dibaca "A Nol Satu Lima"

**Letters (A-C untuk testing):**
```
voice/letters/A.mp3 → "A"
voice/letters/B.mp3 → "B"
voice/letters/C.mp3 → "C"
```

**Words:**
```
voice/words/nomor-antrian.mp3 → "Nomor antrian"
voice/words/silakan.mp3        → "Silakan"
voice/words/ke.mp3             → "Ke"
voice/words/poli.mp3          → "Poli"
```

**Poli:**
```
voice/poli/umum.mp3 → "Umum"
voice/poli/gigi.mp3 → "Gigi"
voice/poli/anak.mp3 → "Anak"
```

### Produksi (Lengkap)

Untuk produksi siap jual, siapkan:
- **Numbers:** 0-100 (atau 0-999 sesuai kebutuhan)
- **Letters:** A-Z
- **Words:** nomor-antrian, silakan, ke, poli, selesai, menunggu
- **Poli:** Semua poli yang ada

---

## Cara Cepat Generate Audio (Script Python)

Berikut script Python untuk generate audio menggunakan gTTS (gratis):

```python
# Install: pip install gtts
from gtts import gTTS
import os

def create_number_audio():
    """Create audio for numbers 0-9 (digit by digit)"""
    numbers_map = {
        0: 'Nol', 1: 'Satu', 2: 'Dua', 3: 'Tiga', 4: 'Empat',
        5: 'Lima', 6: 'Enam', 7: 'Tujuh', 8: 'Delapan', 9: 'Sembilan'
    }

    for num, text in numbers_map.items():
        tts = gTTS(text=text, lang='id')
        tts.save(f'voice/numbers/{num}.mp3')
        print(f'Created: {num}.mp3 ({text})')

def create_letter_audio():
    """Create audio for letters A-Z"""
    letters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ'

    for letter in letters:
        tts = gTTS(text=letter, lang='id')
        tts.save(f'voice/letters/{letter}.mp3')
        print(f'Created: {letter}.mp3')

def create_word_audio():
    """Create audio for common words"""
    words = ['Nomor antrian', 'Silakan', 'Ke', 'Poli']

    for word in words:
        filename = word.lower().replace(' ', '-')
        tts = gTTS(text=word, lang='id')
        tts.save(f'voice/words/{filename}.mp3')
        print(f'Created: {filename}.mp3')

if __name__ == '__main__':
    create_number_audio()
    create_letter_audio()
    create_word_audio()
```

Run script:
```bash
python generate_audio.py
```

---

## Troubleshooting

### Audio tidak bunyi?
1. Cek Console (F12) untuk log error
2. Pastikan file audio ada di folder yang benar
3. Cek path browser inspector (Network tab)

### Suara terpotong?
Pastikan file audio ada jeda (silence) 0.1-0.2 detik di awal dan akhir.

### Playback delay?
Audio dimuat setelah play() dipanggil. Untuk preloading, bisa ditambahkan:

```javascript
// Preload semua audio saat init
preloadAudio() {
    // Preload critical audio files
    ['nomor-antrian', 'silakan', 'ke', 'poli'].forEach(word => {
        const audio = new Audio(`/voice/words/${word}.mp3`);
        audio.load();
    });
}
```

---

## Tips Kualitas Audio

| Parameter | Recommended |
|-----------|-------------|
| Format | MP3 |
| Bitrate | 128kbps |
| Sample Rate | 44.1kHz |
| Channels | Mono |
| Max File Size | 100KB per file |

---

## Alternatif: Beli Paket Audio

Banyak penjual paket audio antrian Indonesia. Cari di:
- Fiverr
- Shopee
- Tokopedia
- freelancer.com

Keywords: "suara antrian", "voice over antrian Indonesia"
