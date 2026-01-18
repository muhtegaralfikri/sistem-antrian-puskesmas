<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Public routes (no authentication required)
$routes->get('/', 'Home::index');

// Kiosk - Public access for taking tickets
$routes->group('kiosk', ['namespace' => 'App\Controllers\Web'], static function ($routes) {
    $routes->get('/', 'KioskController::index');
    $routes->post('ambil', 'KioskController::ambil');
    $routes->get('tiket/(:num)', 'KioskController::tiket/$1');
});

// Display - Public access for TV/monitor display
$routes->group('display', ['namespace' => 'App\Controllers\Web'], static function ($routes) {
    $routes->get('/', 'DisplayController::index');
    $routes->get('data', 'DisplayController::data');
});

// Authentication
$routes->group('auth', ['namespace' => 'App\Controllers\Web'], static function ($routes) {
    $routes->get('login', 'AuthController::loginForm');
    $routes->post('login', 'AuthController::login');
    $routes->get('logout', 'AuthController::logout');
});
$routes->get('login', 'AuthController::loginForm');

// Petugas Dashboard - Auth required
$routes->group('monitor', ['namespace' => 'App\Controllers\Web', 'filter' => 'auth'], static function ($routes) {
    $routes->get('/', 'DashboardController::index');
    $routes->get('data', 'DashboardController::data');
});
$routes->addRedirect('dashboard', 'monitor');

// Admin routes - Admin role required
$routes->group('admin', ['namespace' => 'App\Controllers\Web', 'filter' => 'admin'], static function ($routes) {
    // Main admin page
    $routes->get('/', 'AdminController::index');

    // Antrian management
    $routes->group('antrian', static function ($routes) {
        $routes->get('/', 'AdminAntrianController::index');

        $routes->delete('(:num)', 'AdminAntrianController::delete/$1');
    });

    // Poli management
    $routes->group('poli', static function ($routes) {
        $routes->get('/', 'AdminPoliController::index');
        $routes->post('create', 'AdminPoliController::create');
        $routes->post('update/(:num)', 'AdminPoliController::update/$1');
        $routes->post('delete/(:num)', 'AdminPoliController::delete/$1');
    });

    // User management
    $routes->group('users', static function ($routes) {
        $routes->get('/', 'AdminUsersController::index');
        $routes->get('create', 'AdminUsersController::create');
        $routes->post('create', 'AdminUsersController::create');
        $routes->get('edit/(:num)', 'AdminUsersController::edit/$1');
        $routes->post('edit/(:num)', 'AdminUsersController::edit/$1');
        $routes->post('delete/(:num)', 'AdminUsersController::delete/$1');
    });

    // Reports
    $routes->group('laporan', static function ($routes) {
        $routes->get('harian', 'AdminLaporanController::harian');
        $routes->get('bulanan', 'AdminLaporanController::bulanan');
        $routes->get('export/(:any)', 'AdminLaporanController::export/$1');
    });

    // Settings
    $routes->group('settings', static function ($routes) {
        $routes->get('/', 'AdminSettingsController::index');
        $routes->post('update', 'AdminSettingsController::update');
        $routes->post('reset-antrian/(:num)', 'AdminSettingsController::resetAntrian/$1');
        $routes->post('reset-all', 'AdminSettingsController::resetAllAntrian');
    });

    // Audit Log
    $routes->group('audit-log', static function ($routes) {
        $routes->get('/', 'AdminAuditController::index');
        $routes->get('view/(:num)', 'AdminAuditController::view/$1');
        $routes->get('export', 'AdminAuditController::export');
        $routes->post('clean', 'AdminAuditController::clean');
    });

    // Backup & Restore
    $routes->group('backup', static function ($routes) {
        $routes->get('/', 'AdminBackupController::index');
        $routes->post('create', 'AdminBackupController::create');
        $routes->post('restore', 'AdminBackupController::restore');
        $routes->post('delete', 'AdminBackupController::delete');
        $routes->get('download', 'AdminBackupController::download');
        $routes->post('clean', 'AdminBackupController::clean');
    });
});

// API Routes
$routes->group('api', ['namespace' => 'App\Controllers\Api'], static function ($routes) {
    // Public API (no auth)
    $routes->group('v1', static function ($routes) {
        // Authentication (public - need to login first)
        $routes->post('auth/login', 'AuthController::login');

        // Display data
        $routes->get('display', 'DisplayController::index');

        // Poli list
        $routes->get('poli', 'PoliController::index');

        // Antrian (ambil tiket)
        $routes->post('antrian/ambil', 'AntrianController::ambil');
        $routes->get('antrian/tiket/(:num)', 'AntrianController::tiket/$1');

        // Antrian queue list
        $routes->get('antrian/queue/(:num)', 'AntrianController::queue/$1');
    });

    // Protected API (auth required)
    $routes->group('v1', ['filter' => 'auth'], static function ($routes) {
        // Authentication (protected - need valid session)
        $routes->get('auth/me', 'AuthController::me');
        $routes->post('auth/logout', 'AuthController::logout');

        // Petugas endpoints
        $routes->group('antrian', static function ($routes) {
            $routes->post('panggil', 'AntrianController::panggil');
            $routes->post('recall/(:num)', 'AntrianController::recall/$1');
            $routes->post('selesai/(:num)', 'AntrianController::selesai/$1');
            $routes->post('skip/(:num)', 'AntrianController::skip/$1');
        });

        // Dashboard data
        $routes->get('dashboard', 'DashboardController::index');
    });

    // Admin API (admin role required)
    $routes->group('v1/admin', ['filter' => 'admin'], static function ($routes) {
        // Antrian management
        $routes->get('antrian', 'AdminAntrianController::index');
        $routes->post('antrian/nomor/(:num)', 'AdminAntrianController::updateNomor/$1');
        $routes->delete('antrian/(:num)', 'AdminAntrianController::delete/$1');

        // Poli management
        $routes->get('poli', 'AdminPoliController::index');
        $routes->post('poli', 'AdminPoliController::create');
        $routes->put('poli/(:num)', 'AdminPoliController::update/$1');
        $routes->delete('poli/(:num)', 'AdminPoliController::delete/$1');

        // User management
        $routes->get('users', 'AdminUsersController::index');
        $routes->post('users', 'AdminUsersController::create');
        $routes->put('users/(:num)', 'AdminUsersController::update/$1');
        $routes->delete('users/(:num)', 'AdminUsersController::delete/$1');

        // Reports
        $routes->get('laporan/harian', 'AdminLaporanController::harian');
        $routes->get('laporan/bulanan', 'AdminLaporanController::bulanan');

        // Settings
        $routes->get('settings', 'AdminSettingsController::index');
        $routes->put('settings', 'AdminSettingsController::update');
        $routes->post('reset-antrian/(:num)', 'AdminSettingsController::resetAntrian/$1');
        $routes->post('reset-all', 'AdminSettingsController::resetAll');
    });
});

// WebSocket endpoint (will be handled by separate server)
// Route for informational purposes only
$routes->get('websocket', 'Home::websocketInfo');

/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (is_file(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
