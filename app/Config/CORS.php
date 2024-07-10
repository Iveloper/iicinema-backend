<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class CORS extends BaseConfig
{
    /**
     * --------------------------------------------------------------------------
     * Cross-Origin Resource Sharing (CORS) Settings
     * --------------------------------------------------------------------------
     *
     * Here you can specify your CORS settings for your API.
     * You can allow specific origins, methods, and headers.
     *
     */

    public $allowedOrigins = ['http://localhost:4200']; // Update with the actual origin of your Angular app

    public $allowedMethods = ['*'];

    public $allowedHeaders = ['*'];

    public $exposedHeaders = [];

    public $maxAge = 0;

    public $supportsCredentials = false;
}