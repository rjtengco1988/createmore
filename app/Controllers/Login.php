<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Login extends BaseController
{

    public function index()
    {

        $client_id = env('AWS_CLIENT_ID');
        $redirect_uri = urlencode(env('AWS_REDIRECT_URI'));
        $cognito_domain = env('AWS_COGNITO_DOMAIN');
        $response_type = env('AWS_RESPONSE_TYPE');
        $scopes = env('AWS_SCOPES');

        $login_url = "https://$cognito_domain/login?" .
            "&client_id=$client_id&response_type=$response_type&scope=$scopes&redirect_uri=$redirect_uri";

        return redirect()->to($login_url);
    }


    public function auth()
    {
        $code = $this->request->getGet('code');
        if (!$code) {
            echo "Authorization code not found.";
            exit;
        }

        $client_id = env('AWS_CLIENT_ID');
        $client_secret = env('AWS_SECRET');
        $redirect_uri = env('AWS_REDIRECT_URI'); // DO NOT encode here; encode only in URL query string
        $domain = env('AWS_COGNITO_DOMAIN');     // e.g. yourapp.auth.ap-southeast-1.amazoncognito.com

        $token_url = "https://$domain/oauth2/token";

        $headers = [
            'Authorization: Basic ' . base64_encode("$client_id:$client_secret"),
            'Content-Type: application/x-www-form-urlencoded',
        ];

        $post_fields = http_build_query([
            'grant_type' => 'authorization_code',
            'client_id' => $client_id,
            'code' => $code,
            'redirect_uri' => $redirect_uri,
        ]);

        $ch = curl_init($token_url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curl_error = curl_error($ch);
        curl_close($ch);

        $tokens = json_decode($response, true);

        if (isset($tokens['id_token'])) {
            // Store tokens as needed
            session()->set('id_token', $tokens['id_token']);
            session()->set('access_token', $tokens['access_token']);
            session()->set('refresh_token', $tokens['refresh_token']);

            return redirect()->to('/a/dashboard/');
        } else {
            return redirect()->to('/login-status')->with('error', 'Authentication failed.');
        }
    }


    public function loginStatus()
    {
        $data['title'] = 'Create More';
        echo view('login_status', $data);
    }
}
