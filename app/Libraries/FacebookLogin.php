<?php

namespace App\Libraries;

use Facebook\Facebook;

class FacebookLogin
{
    protected $fb;

    public function __construct()
    {
        $this->fb = new Facebook([
            'app_id' => getenv('facebook.appID'),
            'app_secret' => getenv('facebook.appSecret'),
            'default_graph_version' => 'v19.0',
        ]);
    }

    public function getLoginUrl()
    {
        $helper = $this->fb->getRedirectLoginHelper();
        $permissions = ['email'];
        return $helper->getLoginUrl(getenv('facebook.redirectURL'), $permissions);
    }

    // public function getAccessToken()
    // {
    //     $helper = $this->fb->getRedirectLoginHelper();
    //     return $helper->getAccessToken();
    // }

    // public function getAccessToken()
    // {
    //     try {
    //         $oAuth2Client = $this->fb->getOAuth2Client();

    //         // Get the 'code' manually from the URL
    //         $code = $_GET['code'] ?? null;

    //         // echo $code;
    //         // echo "<BR/>";
    //         // echo "RJ";

    //         if (!$code) {
    //             log_message('error', 'No code returned in the callback.');
    //             return null;
    //         }

    //         $accessToken = $oAuth2Client->getAccessTokenFromCode(
    //             $code,
    //             getenv('facebook.redirectURL')
    //         );

    //         echo var_dump($accessToken);

    //         // return $accessToken;
    //     } catch (\Facebook\Exceptions\FacebookResponseException $e) {
    //         log_message('error', 'Graph error: ' . $e->getMessage());
    //     } catch (\Facebook\Exceptions\FacebookSDKException $e) {
    //         log_message('error', 'SDK error: ' . $e->getMessage());
    //     } catch (\Throwable $e) {
    //         log_message('error', 'General error while manually getting access token: ' . $e->getMessage());
    //     }

    //     return null;
    // }

    public function getAccessToken()
    {
        $code = $_GET['code'] ?? null;

        if (!$code) {
            log_message('error', 'No code provided in the callback.');
            return null;
        }

        $url = 'https://graph.facebook.com/v19.0/oauth/access_token?' . http_build_query([
            'client_id' => getenv('facebook.appID'),
            'redirect_uri' => getenv('facebook.redirectURL'),
            'client_secret' => getenv('facebook.appSecret'),
            'code' => $code,
        ]);

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Only for local self-signed SSL
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);

        log_message('debug', 'Facebook token response (HTTP ' . $httpCode . '): ' . $response);

        if ($httpCode !== 200) {
            log_message('error', "CURL failed: $curlError");
            return null;
        }

        $data = json_decode($response, true);

        if (!isset($data['access_token'])) {
            log_message('error', 'Access token not found in response: ' . $response);
            return null;
        }

        return $data['access_token'];
    }



    public function getUserProfile($accessToken)
    {
        $url = 'https://graph.facebook.com/v19.0/me?fields=id,name,email&access_token=' . urlencode($accessToken);

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Only for self-signed SSL
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);

        log_message('debug', "Facebook user profile response (HTTP $httpCode): $response");

        if ($httpCode !== 200) {
            log_message('error', "Facebook user fetch failed: $curlError");
            return null;
        }

        $data = json_decode($response, true);

        if (isset($data['error'])) {
            log_message('error', 'Facebook returned profile error: ' . $response);
            return null;
        }

        return $data;
    }
}
