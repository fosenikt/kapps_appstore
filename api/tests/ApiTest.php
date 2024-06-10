<?php

require 'BaseTestCase.php';

class ApiTest extends BaseTestCase
{
    public function testGetApps()
    {
        $response = $this->get('/apps/get');

        // Ensure the response is not empty
        $this->assertNotEmpty($response, 'Response should not be empty');

        // Ensure the response is an array
        $this->assertIsArray($response, 'Response should be an array');

        // Ensure the response contains at least one item
        $this->assertNotEmpty($response[0], 'Response should contain at least one item');

        // Check the structure of the first item
        $this->assertArrayHasKey('id', $response[0], 'First item should have "id" key');
        $this->assertArrayHasKey('title', $response[0], 'First item should have "title" key');
        $this->assertArrayHasKey('short_description', $response[0], 'First item should have "short_description" key');
        $this->assertArrayHasKey('primary_image', $response[0], 'First item should have "primary_image" key');
        $this->assertArrayHasKey('type', $response[0], 'First item should have "type" key');
        $this->assertArrayHasKey('tags', $response[0], 'First item should have "tags" key');
        $this->assertArrayHasKey('status', $response[0], 'First item should have "status" key');
    }
}
