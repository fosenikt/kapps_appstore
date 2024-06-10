<?php

require_once __DIR__ . '/../BaseTestCase.php';

class StatsTest extends BaseTestCase
{
    public function testGetLatestPublishedApps()
    {
        // Authenticate and get token
        $this->authenticate();

        // Step 1: Get latest published apps
        $response = $this->get('/stats/apps/latest');

        // Ensure the response is not empty
        $this->assertNotEmpty($response, 'Response should not be empty');

        // Ensure the response is an array
        $this->assertIsArray($response, 'Response should be an array');

        // Ensure the first item in the response contains the expected keys
        if (isset($response[0])) {
            $this->assertArrayHasKey('id', $response[0], 'First app should have "id" key');
            $this->assertArrayHasKey('time_created', $response[0], 'First app should have "time_created" key');
            $this->assertArrayHasKey('title', $response[0], 'First app should have "title" key');
            $this->assertArrayHasKey('short_description', $response[0], 'First app should have "short_description" key');
            $this->assertArrayHasKey('primary_image', $response[0], 'First app should have "primary_image" key');
            $this->assertArrayHasKey('company', $response[0], 'First app should have "company" key');
        }
    }
}
