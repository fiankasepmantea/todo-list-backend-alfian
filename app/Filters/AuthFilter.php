<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Get the Authorization header
        $authHeader = $request->getServer('HTTP_AUTHORIZATION');
        if (!$authHeader) {
            return service('response')->setStatusCode(401)->setBody('Access denied. No token provided.');
        }

        // Extract the token from the header
        $token = str_replace('Bearer ', '', $authHeader);

        try {
            // Decode the token using the secret key
            $key = getenv('JWT_SECRET');
            $decoded = JWT::decode($token, new Key($key, 'HS256'));

            // Attach the userId to the request object
            $request->userId = $decoded->userId; // Add userId to the request
        } catch (\Exception $e) {
            return service('response')->setStatusCode(403)->setBody('Invalid token');
        }

        return $request;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // No action needed
    }
}