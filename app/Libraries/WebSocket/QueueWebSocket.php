<?php

declare(strict_types=1);

namespace App\Libraries\WebSocket;

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

/**
 * Queue WebSocket Server
 *
 * Handles real-time updates for the queue system
 */
class QueueWebSocket implements MessageComponentInterface
{
    protected \SplObjectStorage $clients;
    protected array $subscribers = [];

    public function __construct()
    {
        $this->clients = new \SplObjectStorage();
        log_message('info', 'WebSocket server started');
    }

    /**
     * When a new connection is opened
     */
    public function onOpen(ConnectionInterface $conn): void
    {
        $this->clients->attach($conn);
        $conn->send(json_encode([
            'type' => 'connected',
            'message' => 'Connected to queue server',
            'timestamp' => time(),
        ]));

        log_message('info', "New connection! ({$conn->resourceId})");
    }

    /**
     * When a message is received from a client
     */
    public function onMessage(ConnectionInterface $from, $msg): void
    {
        $data = json_decode($msg, true);

        if (!$data || !isset($data['type'])) {
            return;
        }

        log_message('info', "Connection {$from->resourceId} sending message '{$data['type']}'");

        switch ($data['type']) {
            case 'subscribe':
                $this->handleSubscribe($from, $data);
                break;

            case 'ping':
                $from->send(json_encode([
                    'type' => 'pong',
                    'timestamp' => time(),
                ]));
                break;

            case 'broadcast':
                // Only allow certain broadcast types
                if (isset($data['event']) && in_array($data['event'], [
                    'antrian:baru',
                    'antrian:panggil',
                    'antrian:selesai',
                    'antrian:skip',
                    'display:update',
                ])) {
                    $this->broadcast($data['event'], $data['data'] ?? []);
                }
                break;
        }
    }

    /**
     * Handle subscribe to channels
     */
    protected function handleSubscribe(ConnectionInterface $conn, array $data): void
    {
        $channel = $data['channel'] ?? 'display';

        $conn->resourceId = $conn->resourceId;
        $this->subscribers[$conn->resourceId] = $this->subscribers[$conn->resourceId] ?? [];
        $this->subscribers[$conn->resourceId][] = $channel;
        $this->subscribers[$conn->resourceId] = array_unique($this->subscribers[$conn->resourceId]);

        $conn->send(json_encode([
            'type' => 'subscribed',
            'channel' => $channel,
            'timestamp' => time(),
        ]));

        log_message('info', "Connection {$conn->resourceId} subscribed to channel: {$channel}");
    }

    /**
     * When a connection is closed
     */
    public function onClose(ConnectionInterface $conn): void
    {
        $this->clients->detach($conn);
        unset($this->subscribers[$conn->resourceId]);

        log_message('info', "Connection {$conn->resourceId} has disconnected");
    }

    /**
     * When an error occurs
     */
    public function onError(ConnectionInterface $conn, \Exception $e): void
    {
        log_message('error', "WebSocket error: {$e->getMessage()}");
        $conn->close();
    }

    /**
     * Broadcast to all connected clients
     */
    public function broadcast(string $event, array $data = []): void
    {
        $payload = [
            'event' => $event,
            'data' => $data,
            'timestamp' => time(),
        ];

        $message = json_encode($payload);

        foreach ($this->clients as $client) {
            $client->send($message);
        }

        log_message('info', "Broadcasted event: {$event}");
    }

    /**
     * Broadcast to specific channel subscribers
     */
    public function broadcastToChannel(string $channel, string $event, array $data = []): void
    {
        $payload = [
            'event' => $event,
            'channel' => $channel,
            'data' => $data,
            'timestamp' => time(),
        ];

        $message = json_encode($payload);

        foreach ($this->clients as $client) {
            // Check if client is subscribed to this channel
            $resourceId = $client->resourceId;
            if (isset($this->subscribers[$resourceId]) &&
                in_array($channel, $this->subscribers[$resourceId])) {
                $client->send($message);
            }
        }

        log_message('info', "Broadcasted to channel {$channel}: {$event}");
    }

    /**
     * Notify new antrian
     */
    public function notifyAntrianBaru(int $poliId, string $nomor, int $antrianId): void
    {
        $this->broadcast('antrian:baru', [
            'poli_id' => $poliId,
            'nomor' => $nomor,
            'antrian_id' => $antrianId,
        ]);
    }

    /**
     * Notify antrian called
     */
    public function notifyAntrianPanggil(int $poliId, string $nomor, int $antrianId, array $poli): void
    {
        $this->broadcast('antrian:panggil', [
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
    public function notifyAntrianSelesai(int $poliId, string $nomor, int $antrianId): void
    {
        $this->broadcast('antrian:selesai', [
            'poli_id' => $poliId,
            'nomor' => $nomor,
            'antrian_id' => $antrianId,
        ]);
    }

    /**
     * Notify antrian skipped
     */
    public function notifyAntrianSkip(int $poliId, string $nomor, int $antrianId): void
    {
        $this->broadcast('antrian:skip', [
            'poli_id' => $poliId,
            'nomor' => $nomor,
            'antrian_id' => $antrianId,
        ]);
    }

    /**
     * Notify display update (full data refresh)
     */
    public function notifyDisplayUpdate(array $data): void
    {
        $this->broadcast('display:update', $data);
    }

    /**
     * Get connected clients count
     */
    public function getClientCount(): int
    {
        return $this->clients->count();
    }

    /**
     * Get subscribers info
     */
    public function getSubscribersInfo(): array
    {
        return $this->subscribers;
    }
}
