<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
use Firebase\JWT\JWT;
use Firebase\JWT\JWK;

class CognitoAuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $id_token = session()->get('id_token');

        if (!$id_token) {
            return redirect()->to('/login-status')->with('error', 'No token found. Please log in.');
        }

        $region = env('AWS_REGION');
        $userPoolId = env('AWS_USER_POOL_ID');
        $client_id = env('AWS_CLIENT_ID');

        $expected_issuer = "https://cognito-idp.$region.amazonaws.com/$userPoolId";
        $jwks_url = "https://cognito-idp.$region.amazonaws.com/$userPoolId/.well-known/jwks.json";

        try {
            $jwks_json = file_get_contents($jwks_url);
            if (!$jwks_json) {
                throw new \Exception("Failed to fetch JWKs from $jwks_url");
            }

            $jwks = json_decode($jwks_json, true);
            if (!isset($jwks['keys'])) {
                throw new \Exception("Invalid JWKs format received from AWS Cognito.");
            }

            $keys = JWK::parseKeySet($jwks);
            $decoded = JWT::decode($id_token, $keys);

            if (!in_array($client_id, (array)$decoded->aud)) {
                return redirect()->to('/login-status')->with('error', 'Invalid audience.');
            }

            if ($decoded->iss !== $expected_issuer) {
                return redirect()->to('/login-status')->with('error', 'Invalid issuer.');
            }

            session()->set('user_email', $decoded->email ?? null);
            session()->set('user_id', $decoded->sub ?? null);
        } catch (\Firebase\JWT\ExpiredException $e) {
            return redirect()->to('/login-status')->with('error', 'Session expired. Please log in again.');
        } catch (\Exception $e) {
            log_message('error', 'Token validation failed: ' . $e->getMessage());
            return redirect()->to('/login-status')->with('error', 'Token validation failed: ' . $e->getMessage());
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // No post-processing needed
    }
}
