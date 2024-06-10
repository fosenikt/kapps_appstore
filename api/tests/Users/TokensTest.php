<?php

/* require '../BaseTestCase.php';

class TokensTest extends BaseTestCase
{
    public function testCreateToken()
    {
        $data = [
            'user_id' => 123, // Replace with a valid user ID
            'title' => 'Test Token'
        ];
        $response = $this->post('/admin/user/token/create', $data);

        // Ensure the response is not empty
        $this->assertNotEmpty($response, 'Response should not be empty');

        // Ensure the response contains the token
        $this->assertArrayHasKey('token', $response, 'Response should contain the token');
    }

    public function testGetTokens()
    {
        $userId = 123; // Replace with a valid user ID
        $response = $this->get('/admin/user/token/get/' . $userId);

        // Ensure the response is not empty
        $this->assertNotEmpty($response, 'Response should not be empty');

        // Ensure the response is an array
        $this->assertIsArray($response, 'Response should be an array');

        // Ensure the response contains at least one item
        $this->assertNotEmpty($response[0], 'Response should contain at least one item');

        // Check the structure of the first item
        $this->assertArrayHasKey('id', $response[0], 'First item should have "id" key');
        $this->assertArrayHasKey('title', $response[0], 'First item should have "title" key');
        $this->assertArrayHasKey('time_created', $response[0], 'First item should have "time_created" key');
    }

    public function testDeleteToken()
    {
        $tokenId = 123; // Replace with a valid token ID
        $response = $this->delete('/admin/user/token/delete/' . $tokenId);

        // Ensure the response is not empty
        $this->assertNotEmpty($response, 'Response should not be empty');

        // Ensure the response has success status
        $this->assertEquals('success', $response['status'], 'Response status should be success');
    }
} */
