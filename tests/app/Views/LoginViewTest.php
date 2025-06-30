<?php

namespace Tests\Feature;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\FeatureTestTrait;

class LoginViewTest extends CIUnitTestCase
{
    use FeatureTestTrait;


    public function testLoginPageLoads()
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('Login');
    }

    public function testPrivacyPageLoads()
    {
        $response = $this->get('/privacy-policy');
        $response->assertStatus(200);
        $response->assertSee('Privacy Policy');
    }

    public function testTermsandConditionsPageLoads()
    {
        $response = $this->get('/terms-and-conditions');
        $response->assertStatus(200);
        $response->assertSee('terms and conditions');
    }

    public function testUserDataDeletionPageLoads()
    {
        $response = $this->get('/user-data-deletion');
        $response->assertStatus(200);
        $response->assertSee('User Data Deletion Instructions');
    }
}
