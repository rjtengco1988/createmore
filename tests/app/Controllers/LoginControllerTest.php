<?php

namespace Tests\Feature;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\FeatureTestTrait;

class LoginControllerTest extends CIUnitTestCase
{
    use FeatureTestTrait;
    public function testLoginStatusViewLoadsCorrectly()
    {

        // Send simulated GET request
        $response = $this->get('/login-status');

        // Check for 200 OK (no exception occurred)
        $response->assertOK();

        // Check that the title variable is rendered
        $response->assertSee('Create More');

        // Optional: check that no PHP warning appears
        $this->assertStringNotContainsString('Undefined variable', $response->getBody());
        $this->assertStringNotContainsString('ErrorException', $response->getBody());
    }
}
