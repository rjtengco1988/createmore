<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class AWS extends BaseConfig
{
    public $credentials;
    public $userPoolId;
    public $clientId;

    public function __construct()
    {
        parent::__construct();

        $this->credentials = [
            'region' => env('AWS_REGION'),
            'version' => env('AWS_VERSION'),
            'credentials' => [
                'key'    => env('AWS_ACCESS_KEY'),
                'secret' => env('AWS_SECRET_KEY'),
            ],
        ];

        $this->userPoolId = env('AWS_USER_POOL_ID');
        $this->clientId   = env('AWS_CLIENT_ID');
    }
}
