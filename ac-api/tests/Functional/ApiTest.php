<?php

namespace Tests\Functional;

class ApiTest extends BaseTestCase
{


    /**
     * Test that the get Device route with optional name argument returns a rendered greeting
     */
    public function testGetDeviceList()
    {
        $response = $this->runApp('GET', '/api/v1/device/list');
        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * Test that the get Device route won't accept a post request
     */
    public function testGetDeviceListPostNotAllowed()
    {
        $response = $this->runApp('POST', '/api/v1/device/list', ['test']);

        $this->assertEquals(405, $response->getStatusCode());
        $this->assertContains('Method not allowed', (string)$response->getBody());
    }

}