<?php

declare(strict_types=1);

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Services;

/**
 * Admin Filter
 *
 * Check if user is admin
 */
class AdminFilter implements FilterInterface
{
    /**
     * Do whatever processing this filter needs to do.
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();

        // First check if logged in
        if (!$session->get('user_id')) {
            if ($request->isAJAX() || strpos($request->getHeaderLine('Accept'), 'application/json') !== false) {
                return Services::response()
                    ->setStatusCode(401)
                    ->setJSON([
                        'success' => false,
                        'message' => 'Unauthorized. Please login.',
                    ]);
            }
            return redirect()->to('/login');
        }

        // Then check if admin
        if ($session->get('user_role') !== 'admin') {
            if ($request->isAJAX() || strpos($request->getHeaderLine('Accept'), 'application/json') !== false) {
                return Services::response()
                    ->setStatusCode(403)
                    ->setJSON([
                        'success' => false,
                        'message' => 'Forbidden. Admin access required.',
                    ]);
            }
            return redirect()->to('/dashboard')->with('error', 'Anda tidak memiliki akses ke halaman tersebut.');
        }
    }

    /**
     * We don't need to do anything after.
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        return $response;
    }
}
