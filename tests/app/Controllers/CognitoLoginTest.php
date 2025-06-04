<?php

namespace Tests\Unit;

use CodeIgniter\Test\CIUnitTestCase;
use App\Libraries\CognitoService;
use Config\AWS;

class CognitoLoginTest extends CIUnitTestCase
{
    protected CognitoService $cognito;
    protected AWS $awsConfig;
    protected string $testUser;
    protected string $testPass;

    protected function setUp(): void
    {
        parent::setUp();

        // Load config and service
        $this->awsConfig = new AWS();
        $this->cognito = new CognitoService();

        // Load test credentials from .env
        $this->testUser = env('TEST_COGNITO_USER');
        $this->testPass = env('TEST_COGNITO_PASS');
    }

    public function testAwsConfigLoadsFromEnv()
    {
        $this->assertEquals(env('AWS_REGION'), $this->awsConfig->credentials['region']);
        $this->assertEquals(env('AWS_VERSION'), $this->awsConfig->credentials['version']);
        $this->assertEquals(env('AWS_ACCESS_KEY'), $this->awsConfig->credentials['credentials']['key']);
        $this->assertEquals(env('AWS_SECRET_KEY'), $this->awsConfig->credentials['credentials']['secret']);
        $this->assertEquals(env('AWS_USER_POOL_ID'), $this->awsConfig->userPoolId);
        $this->assertEquals(env('AWS_CLIENT_ID'), $this->awsConfig->clientId);
    }

    public function testLoginWithValidCognitoUser()
    {
        $this->assertNotEmpty($this->testUser, 'TEST_COGNITO_USER not set in .env');
        $this->assertNotEmpty($this->testPass, 'TEST_COGNITO_PASS not set in .env');

        $response = $this->cognito->login($this->testUser, $this->testPass);

        $this->assertIsArray($response);

        if (isset($response['error'])) {
            $this->fail('Login failed: ' . $response['error']);
        }

        $this->assertArrayHasKey('AccessToken', $response);
        $this->assertArrayHasKey('IdToken', $response);
    }

    public function testLoginWithInvalidCredentials()
    {
        $invalidUsername = 'invaliduser@example.com';
        $invalidPassword = 'WrongPassword123';

        $response = $this->cognito->login($invalidUsername, $invalidPassword);

        $this->assertIsArray($response);
        $this->assertArrayHasKey('error', $response);
        $this->assertStringContainsStringIgnoringCase('Incorrect', $response['error']);
    }
}
