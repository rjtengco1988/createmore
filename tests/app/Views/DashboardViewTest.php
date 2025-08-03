<?php

namespace Tests\Feature;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\FeatureTestTrait;

class DashboardViewTest extends CIUnitTestCase
{
    use FeatureTestTrait;

    public function testDasboardPageLoads()
    {
        $response = $this->get('/a/dashboard/');
        $response->assertStatus(302);
    }
}
