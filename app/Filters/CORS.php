<?php

namespace App\Filters;

use CodeIgniter\Config\Services;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class CORS implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // // Set CORS headers for all requests
        // $response = Services::response();
        // $response->setHeader('Access-Control-Allow-Origin', '*');
        // $response->setHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
        // $response->setHeader('Access-Control-Allow-Headers', 'Authorization, Content-Type, X-Requested-With');

        // // Handle OPTIONS request explicitly
        // if ($request->getMethod() === 'OPTIONS') {
        //     // Set the Access-Control-Allow-Methods header
        //     $response->setHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
        //     return $response;
        // }

        $response = service('response');
        $response->setHeader('Access-Control-Allow-Origin', '*')
                 ->setHeader('Access-Control-Allow-Methods', '*')
                 ->setHeader('Access-Control-Allow-Headers', '*');

        if ($request->getMethod() === 'options') {
            $response->setStatusCode(200);
            return $response;
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do nothing
    }
}