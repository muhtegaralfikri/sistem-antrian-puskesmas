<?php

declare(strict_types=1);

namespace App\Libraries\WebSocket;

/**
 * WebSocket Helper
 *
 * Helper class to broadcast events from controllers
 * Uses a simple file-based queue system for cross-process communication
 */
class WebSocketHelper
{
    private static string $queueDir = WRITEPATH . 'websocket_queue';

    /**
     * Broadcast an event to all connected WebSocket clients
     *
     * @param string $event Event name (e.g., 'antrian:panggil')
     * @param array $data Event data
     * @return bool
     */
    public static function broadcast(string $event, array $data = []): bool
    {
        // Create queue directory if not exists
        if (!is_dir(self::$queueDir)) {
            mkdir(self::$queueDir, 0755, true);
        }

        $payload = [
            'event' => $event,
            'data' => $data,
            'timestamp' => time(),
        ];

        // Write to queue file
        $queueFile = self::$queueDir . '/' . uniqid('ws_', true) . '.json';

        $result = file_put_contents($queueFile, json_encode($payload));

        return $result !== false;
    }

    /**
     * Notify new antrian
     */
    public static function antrianBaru(int $poliId, string $nomor, int $antrianId): bool
    {
        return self::broadcast('antrian:baru', [
            'poli_id' => $poliId,
            'nomor' => $nomor,
            'antrian_id' => $antrianId,
        ]);
    }

    /**
     * Notify antrian called
     */
    public static function antrianPanggil(int $poliId, string $nomor, int $antrianId, array $poli): bool
    {
        return self::broadcast('antrian:panggil', [
            'poli_id' => $poliId,
            'nomor' => $nomor,
            'antrian_id' => $antrianId,
            'poli_nama' => $poli['nama'] ?? '',
            'prefix' => $poli['prefix'] ?? '',
        ]);
    }

    /**
     * Notify antrian completed
     */
    public static function antrianSelesai(int $poliId, string $nomor, int $antrianId): bool
    {
        return self::broadcast('antrian:selesai', [
            'poli_id' => $poliId,
            'nomor' => $nomor,
            'antrian_id' => $antrianId,
        ]);
    }

    /**
     * Notify antrian skipped
     */
    public static function antrianSkip(int $poliId, string $nomor, int $antrianId): bool
    {
        return self::broadcast('antrian:skip', [
            'poli_id' => $poliId,
            'nomor' => $nomor,
            'antrian_id' => $antrianId,
        ]);
    }

    /**
     * Notify display update (full data refresh)
     */
    public static function displayUpdate(array $data): bool
    {
        return self::broadcast('display:update', $data);
    }

    /**
     * Process queued broadcasts (called by WebSocket server)
     *
     * @param QueueWebSocket $server
     * @return int Number of events processed
     */
    public static function processQueue(QueueWebSocket $server): int
    {
        if (!is_dir(self::$queueDir)) {
            return 0;
        }

        $files = glob(self::$queueDir . '/*.json');
        $processed = 0;

        foreach ($files as $file) {
            $content = file_get_contents($file);
            if ($content === false) {
                continue;
            }

            $payload = json_decode($content, true);
            if (!$payload || !isset($payload['event'])) {
                @unlink($file);
                continue;
            }

            // Broadcast to server
            $server->broadcast($payload['event'], $payload['data'] ?? []);

            // Remove queue file
            @unlink($file);
            $processed++;
        }

        return $processed;
    }

    /**
     * Clear all queued broadcasts
     */
    public static function clearQueue(): int
    {
        if (!is_dir(self::$queueDir)) {
            return 0;
        }

        $files = glob(self::$queueDir . '/*.json');
        $cleared = 0;

        foreach ($files as $file) {
            if (@unlink($file)) {
                $cleared++;
            }
        }

        return $cleared;
    }
}
