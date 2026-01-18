"""
Script untuk generate audio files menggunakan gTTS (gratis)
Untuk testing/development sistem antrian.

Requirement:
    pip install gtts

Usage:
    python generate_audio.py
"""

from gtts import gTTS
import os

def create_folders():
    """Buat folder untuk audio files"""
    folders = ['voice/numbers', 'voice/letters', 'voice/words', 'voice/poli']
    for folder in folders:
        os.makedirs(folder, exist_ok=True)
    print("[OK] Folders created")

def create_number_audio():
    """Create audio for numbers 0-9 (digit by digit)"""
    numbers_map = {
        0: 'Nol', 1: 'Satu', 2: 'Dua', 3: 'Tiga', 4: 'Empat',
        5: 'Lima', 6: 'Enam', 7: 'Tujuh', 8: 'Delapan', 9: 'Sembilan',
        'sepuluh': 'Sepuluh',
        'sebelas': 'Sebelas',
        'belas': 'Belas',
        'puluh': 'Puluh',
        'seratus': 'Seratus',
        'ratus': 'Ratus'
    }

    print("\n[*] Creating numbers 0-9 and helpers...")
    for num, text in numbers_map.items():
        tts = gTTS(text=text, lang='id')
        filename = f'voice/numbers/{num}.mp3'
        tts.save(filename)
        print(f"  [OK] {filename} - '{text}'")

def create_letter_audio():
    """Create audio for letters A-Z"""
    letters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ'

    print("\n[*] Creating letters A-Z...")
    for letter in letters:
        tts = gTTS(text=letter, lang='id')
        filename = f'voice/letters/{letter}.mp3'
        tts.save(filename)
        print(f"  [OK] {filename} - '{letter}'")

def create_word_audio():
    """Create audio for common words"""
    words = {
        'nomor-antrian': 'Nomor antrian',
        'silakan': 'Silakan',
        'ke': 'Ke',
        'poli': 'Poli'
    }

    print("\n[*] Creating words...")
    for word, text in words.items():
        tts = gTTS(text=text, lang='id')
        filename = f'voice/words/{word}.mp3'
        tts.save(filename)
        print(f"  [OK] {filename} - '{text}'")

def create_poli_audio():
    """Create audio for poli names"""
    polis = {
        'umum': 'Umum',
        'gigi': 'Gigi',
        'anak': 'Anak'
    }

    print("\n[*] Creating poli names...")
    for poli, text in polis.items():
        tts = gTTS(text=text, lang='id')
        filename = f'voice/poli/{poli}.mp3'
        tts.save(filename)
        print(f"  [OK] {filename} - '{text}'")

if __name__ == '__main__':
    print("=" * 50)
    print("Generating Audio Files for Queue System")
    print("=" * 50)

    create_folders()
    create_number_audio()
    create_letter_audio()
    create_word_audio()
    create_poli_audio()

    print("\n" + "=" * 50)
    print("[OK] All audio files generated successfully!")
    print("=" * 50)
    print("\nMove the 'voice' folder to: public/voice/")
    print("Run: move voice public\\")
    print("=" * 50)
