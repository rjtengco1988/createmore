<?php

namespace App\Libraries;

use Aws\CognitoIdentityProvider\CognitoIdentityProviderClient;
use Config\AWS;

class CognitoService
{
    protected $client;
    protected $config;

    public function __construct()
    {
        $this->config = new AWS();
        $this->client = new CognitoIdentityProviderClient($this->config->credentials);
    }

    public function login($username, $password)
    {
        try {
            $result = $this->client->initiateAuth([
                'AuthFlow' => 'USER_PASSWORD_AUTH',
                'AuthParameters' => [
                    'USERNAME' => $username,
                    'PASSWORD' => $password,
                ],
                'ClientId' => $this->config->clientId,
            ]);
            return $result->get('AuthenticationResult');
        } catch (\Aws\Exception\AwsException $e) {
            return ['error' => $e->getAwsErrorMessage()];
        }
    }

    public function signup($username, $password, $email)
    {
        try {
            $result = $this->client->signUp([
                'ClientId' => $this->config->clientId,
                'Username' => $username,
                'Password' => $password,
                'UserAttributes' => [
                    [
                        'Name'  => 'email',
                        'Value' => $email,
                    ],
                ],
            ]);
            return $result;
        } catch (\Aws\Exception\AwsException $e) {
            return ['error' => $e->getAwsErrorMessage()];
        }
    }
}
