<?php

namespace App\Controllers;

use App\Libraries\CognitoService;
use App\Libraries\FacebookLogin;
use Firebase\JWT\JWT;
use Firebase\JWT\JWK;
use GuzzleHttp\Client;


class Auth extends BaseController
{
    protected $cognito;
    private $session;

    public function __construct()
    {
        $this->cognito = new CognitoService();
        $this->request = \Config\Services::request();
        $this->session = \Config\Services::session();
    }

    public function login()
    {

        $data['title'] = "Create More";
        if ($this->request->getMethod() == 'POST') {
            $username = $this->request->getPost('username');
            $password = $this->request->getPost('password');

            $response = $this->cognito->login($username, $password);

            if (isset($response['error'])) {
                $data['error'] = $response['error'];
            } else {

                $accessToken = $response['AccessToken'];


                try {
                    // Cache the JWKS locally (simple caching)
                    $jwksUrl = "https://cognito-idp.ap-southeast-1.amazonaws.com/ap-southeast-1_F78yVViq1/.well-known/jwks.json";
                    $cacheFile = WRITEPATH . 'cache/jwks.json';

                    if (!file_exists($cacheFile) || filemtime($cacheFile) < time() - 86400) {
                        file_put_contents($cacheFile, file_get_contents($jwksUrl));
                    }

                    $jwks = file_get_contents($cacheFile);
                    $keys = JWK::parseKeySet(json_decode($jwks, true));

                    $decoded = JWT::decode($accessToken, $keys);

                    // Validate iss and aud
                    $expectedIssuer = "https://cognito-idp.ap-southeast-1.amazonaws.com/ap-southeast-1_F78yVViq1";

                    if ($decoded->iss !== $expectedIssuer) {
                        throw new \Exception("Invalid token issuer");
                    }

                    // Set session
                    session()->set([
                        'user_id' => $decoded->{'username'} ?? $decoded->sub ?? null,
                        'access_token' => $accessToken
                    ]);


                    return redirect()->to('/a/dashboard/');
                } catch (\Exception $e) {
                    $data['error'] = 'Token verification failed: ' . $e->getMessage();
                }
            }
        }


        return view('login', $data);
    }

    public function facebookLogin()
    {
        $fb = new FacebookLogin();
        return redirect()->to($fb->getLoginUrl());
    }

    public function facebookCallback()
    {
        $fb = new \App\Libraries\FacebookLogin();

        try {
            $accessToken = $fb->getAccessToken();
        } catch (\Throwable $e) {
            log_message('error', 'Error getting access token: ' . $e->getMessage());
            return redirect()->to('/login')->with('error', 'Facebook login failed.');
        }

        if (!isset($accessToken)) {
            log_message('error', 'Access token was not returned by Facebook.');
            return redirect()->to('/login')->with('error', 'Facebook did not return an access token.');
        }

        $user = $fb->getUserProfile($accessToken);

        if ($user) {
            session()->set([
                'isLoggedIn' => true,
                'userName' => $user['name'],
                'userEmail' => $user['email'],
            ]);
            return redirect()->to('/a/dashboard');
        }

        log_message('error', 'User profile could not be fetched.');
        return redirect()->to('/login')->with('error', 'Facebook login failed.');
    }
}
