<?php

namespace App\Helpers;

class VoiceHelper
{
    /**
     * Generate MP3 file from text using Google TTS
     * 
     * @param string $text Text to speak
     * @param string $outputPath Full path to save the file
     * @param string $lang Language code (default 'id')
     * @return bool Success status
     */
    public static function generate($text, $outputPath, $lang = 'id')
    {
        try {
            $text = urlencode($text);
            $url = "https://translate.google.com/translate_tts?ie=UTF-8&client=tw-ob&q={$text}&tl={$lang}";

            // Initialize cURL
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36');
            
            $audioData = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($httpCode === 200 && $audioData) {
                // Ensure directory exists
                $dir = dirname($outputPath);
                if (!is_dir($dir)) {
                    mkdir($dir, 0777, true);
                }

                return file_put_contents($outputPath, $audioData) !== false;
            }

            return false;
        } catch (\Exception $e) {
            log_message('error', 'VoiceHelper Error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Convert poli name to slug format (matching JS logic)
     */
    public static function getSlug($name)
    {
        $slug = strtolower($name);
        
        // Remove "poli " prefix
        if (str_starts_with($slug, 'poli ')) {
            $slug = substr($slug, 5);
        } elseif (str_starts_with($slug, 'poli')) {
            $slug = substr($slug, 4);
        }

        // Replace non-alphanumeric with underscore to match frontend JS logic
        return preg_replace('/[^a-z0-9]/', '_', $slug);
    }
}
