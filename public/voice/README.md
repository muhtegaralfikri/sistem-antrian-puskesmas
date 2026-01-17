# Audio Files for Queue System

This folder contains pre-recorded audio files for queue announcements.

## Folder Structure

```
voice/
├── bel.mp3      # Suara bell "ting-tung-ting" sebelum pengumuman
├── numbers/    # Angka 0-9 (digit-by-digit: 0.mp3, 1.mp3, ..., 9.mp3)
├── letters/     # Huruf A-Z (contoh: A.mp3, B.mp3, ..., Z.mp3)
├── words/       # Kata-kata umum
│   ├── nomor-antrian.mp3    # "Nomor antrian"
│   ├── silakan.mp3           # "Silakan"
│   ├── ke.mp3                # "Ke"
│   ├── poli.mp3              # "Poli"
│   └── selesai.mp3           # "Selesai" (opsional)
└── poli/        # Nama poli
    ├── umum.mp3              # "Umum"
    ├── gigi.mp3              # "Gigi"
    └── anak.mp3              # "Anak"
```

## Announcement Flow

```
Bell (ting-tung-ting) → "Nomor antrian" → Huruf → Angka (digit) → "Silakan ke Poli" → Nama Poli

Example: A-001 → bel → "Nomor antrian" → "A" → "Nol" → "Nol" → "Satu" → "Silakan" → "Ke" → "Poli" → "Umum"
```

## Recording Format

- **Format:** MP3 (recommended) or WAV
- **Quality:** 128kbps (MP3) or 44.1kHz 16bit (WAV)
- **Voice:** Professional voice talent (male/female)
- **Language:** Indonesian

## Recording Script

### Numbers (0-100)
- "Nol", "Satu", "Dua", ..., "Seratus"

### Letters (A-Z)
- "A", "B", "C", ..., "Z" (baca hurufnya, bukan phonetic)

### Words
- "Nomor antrian"
- "Silakan"
- "Ke"
- "Poli"

### Poli Names
- "Umum"
- "Gigi"
- "Anak"
- (sesuai poli yang ada)

## Example File Generation

You can use free TTS tools to generate initial audio files:
- https://www.soundoftext.com/ (Indonesian voice available)
- Windows TTS → Record with Audacity
- Online TTS services

## Naming Convention

- Use **exact same filename** as specified
- Use **MP3 format** for web compatibility
- Keep file size small (under 100KB per file)

## Placeholder Files

For testing, you can use any MP3 files as placeholders.
The system will still work even with placeholder files.
