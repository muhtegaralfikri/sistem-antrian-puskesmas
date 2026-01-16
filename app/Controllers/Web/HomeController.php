<?php

declare(strict_types=1);

namespace App\Controllers\Web;

use App\Controllers\BaseController;

class HomeController extends BaseController
{
    public function index()
    {
        return redirect()->to('/kiosk');
    }

    /**
     * WebSocket info page
     */
    public function websocketInfo()
    {
        return $this->response->setJSON([
            'message' => 'WebSocket server runs separately',
            'start_command' => 'php spark websocket:start',
            'default_port' => 8080,
        ]);
    }
}
