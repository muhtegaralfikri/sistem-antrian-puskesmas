<?php

declare(strict_types=1);

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * Authentication Filter
 *
 * Check if user is logged in
 */
class AuthFilter implements FilterInterface
{
    /**
     * Do whatever processing this filter needs to do.
     * By default it should not return anything during
     * normal execution. However, when an abnormal state
     * is found, it should return an instance of
     * CodeIgniter\HTTP\Response. If it does, script
     * execution will end and that Response will be
     * sent back to the client, allowing for error pages,
     * redirects, etc.
     *
     * @param RequestInterface $request
     * @param null             $arguments
     *
     * @return mixed
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();

        if (!$session->get('user_id')) {
            // Check if API request
            if ($request->isAJAX() || strpos($request->getHeaderLine('Accept'), 'application/json') !== false) {
                return Services::response()
                    ->setStatusCode(401)
                    ->setJSON([
                        'success' => false,
                        'message' => 'Unauthorized. Please login.',
                    ]);
            }

            // Redirect to login
            return redirect()->to('/auth/login');
        }
    }

    /**
     * We don't need to do anything after.
     *
     * @param RequestInterface  $request
     * @param ResponseInterface $response
     * @param null              $arguments
     *
     * @return mixed
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        return $response;
    }
}
