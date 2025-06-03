<?php

namespace Tests\Feature;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\FeatureTestTrait;

class LoginViewTest extends CIUnitTestCase
{
    use FeatureTestTrait;


    public function testLoginPageLoads()
    {
        $response = $this->get('/a/login/');
        $response->assertStatus(200);
        $response->assertSee('Login');
    }
}
