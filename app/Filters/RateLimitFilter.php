<?php

declare(strict_types=1);

namespace App\Filters;

use CodeIgniter\Config\Services;
use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * Rate Limit Filter
 *
 * Mencegah brute force attack dengan membatasi jumlah request
 */
class RateLimitFilter implements FilterInterface
{
    /**
     * Konfigurasi rate limit
     */
    private array $config = [
        'max_requests' => 5,        // Maksimal request
        'time_window' => 60,        // Dalam hitungan detik (60 detik)
        'block_duration' => 300,    // Durasi blokir (5 menit)
    ];

    /**
     * Do whatever processing this filter needs to do.
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();
        $ip = $request->getIPAddress();
        $uri = $request->getUri()->getPath();

        // Buat key unik untuk rate limiting
        $rateLimitKey = "rate_limit_{$ip}_{$uri}";

        // Ambil data rate limit dari session
        $rateLimitData = $session->get($rateLimitKey);

        $now = time();

        // Cek apakah sedang diblokir
        if ($rateLimitData && isset($rateLimitData['blocked_until']) && $rateLimitData['blocked_until'] > $now) {
            $remainingTime = $rateLimitData['blocked_until'] - $now;

            return Services::response()
                ->setStatusCode(429)
                ->setJSON([
                    'success' => false,
                    'message' => "Terlalu banyak percobaan. Silakan coba lagi dalam {$remainingTime} detik.",
                    'retry_after' => $remainingTime,
                ]);
        }

        // Inisialisasi data jika belum ada
        if (!$rateLimitData) {
            $rateLimitData = [
                'requests' => 0,
                'window_start' => $now,
                'blocked_until' => 0,
            ];
        }

        // Reset window jika sudah lewat time window
        if (($now - $rateLimitData['window_start']) > $this->config['time_window']) {
            $rateLimitData['requests'] = 0;
            $rateLimitData['window_start'] = $now;
        }

        // Tambah counter request
        $rateLimitData['requests']++;

        // Cek apakah melebihi batas
        if ($rateLimitData['requests'] > $this->config['max_requests']) {
            // Set blokir
            $rateLimitData['blocked_until'] = $now + $this->config['block_duration'];
            $session->set($rateLimitKey, $rateLimitData);

            return Services::response()
                ->setStatusCode(429)
                ->setJSON([
                    'success' => false,
                    'message' => "Terlalu banyak percobaan. Silakan coba lagi dalam {$this->config['block_duration']} detik.",
                    'retry_after' => $this->config['block_duration'],
                ]);
        }

        // Simpan data rate limit
        $session->set($rateLimitKey, $rateLimitData);

        // Tambah header rate limit ke response
        // Tambah header rate limit ke response
        $response = Services::response();
        $response->setHeader('X-RateLimit-Limit', (string) $this->config['max_requests']);
        $response->setHeader('X-RateLimit-Remaining', (string) ($this->config['max_requests'] - $rateLimitData['requests']));
        $response->setHeader('X-RateLimit-Reset', (string) ($rateLimitData['window_start'] + $this->config['time_window']));
    }

    /**
     * We don't need to do anything after.
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        return $response;
    }
}
