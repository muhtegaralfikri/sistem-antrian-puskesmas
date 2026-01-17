<?php

declare(strict_types=1);

namespace App\Controllers\Api;

use App\Controllers\BaseController;

/**
 * Voice API Controller
 * Text-to-speech offline menggunakan Windows SAPI via PowerShell
 */
class VoiceController extends BaseController
{
    /**
     * Generate audio menggunakan Windows SAPI (via PowerShell/VBScript)
     * POST /api/v1/voice/speak
     */
    public function speak()
    {
        $text = $this->request->getPost('text');

        if (!$text) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'Text is required',
            ]);
        }

        try {
            // Cek OS - hanya Windows yang support SAPI
            if (PHP_OS_FAMILY !== 'Windows') {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Windows SAPI only works on Windows',
                    'fallback' => true,
                ]);
            }

            // Bersihkan text untuk VBScript
            $cleanText = str_replace(['"', "'"], '', $text);

            // Cari voice Indonesia dulu, kalau tidak ada pakai English
            $vbsContent = <<<VBS
Set Voice = CreateObject("SAPI.SpVoice")
Dim voices, foundVoice
Set voices = Voice.GetVoices()
For Each v In voices
    If InStr(v.GetDescription(), "Indonesia") > 0 Or InStr(v.GetDescription(), "Andika") > 0 Then
        Set foundVoice = v
        Exit For
    End If
Next
If IsObject(foundVoice) Then
    Set Voice.Voice = foundVoice
Else
    ' Pakai voice pertama (English)
End If
Voice.Speak "$cleanText"
VBS;

            $vbsFile = WRITEPATH . 'temp_speak.vbs';
            file_put_contents($vbsFile, $vbsContent);

            // Jalankan VBScript
            $output = shell_exec('cscript //nologo "' . $vbsFile . '" 2>&1');

            // Hapus file temporary
            @unlink($vbsFile);

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Text spoken successfully',
                'output' => $output,
            ]);
        } catch (\Exception $e) {
            return $this->response->setStatusCode(500)->setJSON([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
                'fallback' => true,
            ]);
        }
    }

    /**
     * Test endpoint untuk mengecek apakah Windows SAPI tersedia
     * GET /api/v1/voice/test
     */
    public function test()
    {
        if (PHP_OS_FAMILY !== 'Windows') {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Not Windows - SAPI not available',
                'os' => PHP_OS_FAMILY,
            ]);
        }

        try {
            $testFile = WRITEPATH . 'temp_voice_test.vbs';
            $vbsContent = <<<VBS
Set Voice = CreateObject("SAPI.SpVoice")
For Each v In Voice.GetVoices
    WScript.Echo v.GetDescription() & "|" & v.Id
Next
VBS;

            file_put_contents($testFile, $vbsContent);
            $output = shell_exec('cscript //nologo "' . $testFile . '" 2>&1');
            @unlink($testFile);

            $voices = [];
            if ($output) {
                $lines = explode("\n", trim($output));
                foreach ($lines as $line) {
                    if (strpos($line, '|') !== false) {
                        $parts = explode('|', $line);
                        $voices[] = [
                            'name' => trim($parts[0]),
                            'id' => trim($parts[1] ?? ''),
                        ];
                    }
                }
            }

            return $this->response->setJSON([
                'success' => true,
                'os' => PHP_OS_FAMILY,
                'voices' => $voices,
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Cek ketersediaan voice
     * GET /api/v1/voice/available
     */
    public function available()
    {
        return $this->response->setJSON([
            'success' => PHP_OS_FAMILY === 'Windows',
            'os' => PHP_OS_FAMILY,
            'php_os' => PHP_OS,
            'method' => 'Windows SAPI via VBScript',
        ]);
    }
}
