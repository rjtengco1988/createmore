<?php

namespace Tests\Feature;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\FeatureTestTrait;

class PermissionViewTest extends CIUnitTestCase
{
    use FeatureTestTrait;

    public function testPermissionPageLoads()
    {
        $response = $this->get('/a/permissions/');
        $response->assertStatus(302);
    }
}
