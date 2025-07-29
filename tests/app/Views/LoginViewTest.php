<?php

namespace Tests\Feature;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\FeatureTestTrait;

class LoginViewTest extends CIUnitTestCase
{
    use FeatureTestTrait;


    public function testLoginPageLoads()
    {
        $response = $this->get('/a/login');

        // 1. Assert it redirects (302)
        $response->assertStatus(302);

        // 2. Assert the redirect URL is correct (you can be strict or partial)
        $redirectUrl = $response->getRedirectUrl();

        // 3. Basic check: it contains expected Cognito base URL
        $this->assertStringContainsString('https://ap-southeast-14uigpc7az.auth.ap-southeast-1.amazoncognito.com/login', $redirectUrl);
    }

    public function testLoginStatusPageLoads()
    {
        $response = $this->get('/login-status');
        $response->assertStatus(200);
        $response->assertSee('Login Failed');
    }
}
