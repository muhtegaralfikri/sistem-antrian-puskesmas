<?php

declare(strict_types=1);

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use App\Libraries\WebSocket\QueueWebSocket;

/**
 * WebSocket Server Command
 *
 * Start the WebSocket server for real-time queue updates
 */
class WebSocketServer extends BaseCommand
{
    /**
     * The Command's Group
     *
     * @var string
     */
    protected $group = 'Queue';

    /**
     * The Command's Name
     *
     * @var string
     */
    protected $name = 'websocket:start';

    /**
     * The Command's Description
     *
     * @var string
     */
    protected $description = 'Start the WebSocket server for real-time queue updates';

    /**
     * The Command's Usage
     *
     * @var string
     */
    protected $usage = 'websocket:start [host] [port]';

    /**
     * The Command's Arguments
     *
     * @var array
     */
    protected $arguments = [
        'host' => 'WebSocket server host (default: 0.0.0.0)',
        'port' => 'WebSocket server port (default: 8080)',
    ];

    /**
     * Actually execute a command.
     */
    public function run(array $params): void
    {
        $host = $params[0] ?? '0.0.0.0';
        $port = (int) ($params[1] ?? 8080);

        CLI::write('=== Starting WebSocket Server ===', 'green');
        CLI::write("Host: {$host}");
        CLI::write("Port: {$port}");
        CLI::write('Press Ctrl+C to stop');
        CLI::write('----------------------------------');

        try {
            // Run the server
            $this->startServer($host, $port);
        } catch (\Exception $e) {
            CLI::write('Error: ' . $e->getMessage(), 'red');
            CLI::write('Make sure Ratchet is installed: composer require cboden/ratchet', 'yellow');
            exit(1);
        }
    }

    /**
     * Start the WebSocket server
     */
    protected function startServer(string $host, int $port): void
    {
        // Check if Ratchet is available
        if (!class_exists('Ratchet\Server\IoServer')) {
            throw new \Exception('Ratchet is not installed. Run: composer require cboden/ratchet');
        }

        // Create WebSocket app
        $app = new QueueWebSocket();

        // Create server
        $server = \Ratchet\Server\IoServer::factory(
            new \Ratchet\Http\HttpServer(
                new \Ratchet\WebSocket\WsServer($app)
            ),
            $port,
            $host
        );

        // Display status
        CLI::write('WebSocket server is running!', 'green');
        CLI::write("Connect to: ws://{$host}:{$port}", 'cyan');
        CLI::write('----------------------------------');
        CLI::write('');

        // Run the server (blocking)
        $server->run();
    }
}
