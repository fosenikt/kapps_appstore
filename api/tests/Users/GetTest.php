<?php

require_once __DIR__ . '/../BaseTestCase.php';

class GetTest extends BaseTestCase
{
    public function testGetUsers()
    {
        // Authenticate and get token
        $this->authenticate();

        // Step 1: Get all users
        $response = $this->get('/users/get');

        // Ensure the response is not empty
        $this->assertNotEmpty($response, 'Response should not be empty');

        // Ensure the response is an array
        $this->assertIsArray($response, 'Response should be an array');

        // Ensure the first item in the response contains the expected keys
        if (isset($response[0])) {
            $this->assertArrayHasKey('id', $response[0], 'First user should have "id" key');
            $this->assertArrayHasKey('o365_id', $response[0], 'First user should have "o365_id" key');
            $this->assertArrayHasKey('customer', $response[0], 'First user should have "customer" key');
            $this->assertArrayHasKey('firstname', $response[0], 'First user should have "firstname" key');
            $this->assertArrayHasKey('lastname', $response[0], 'First user should have "lastname" key');
            $this->assertArrayHasKey('initials', $response[0], 'First user should have "initials" key');
            $this->assertArrayHasKey('mail', $response[0], 'First user should have "mail" key');
            $this->assertArrayHasKey('mobile', $response[0], 'First user should have "mobile" key');
            $this->assertArrayHasKey('status', $response[0], 'First user should have "status" key');
            $this->assertArrayHasKey('photo', $response[0], 'First user should have "photo" key');
            $this->assertArrayHasKey('company_role', $response[0], 'First user should have "company_role" key');
            $this->assertArrayHasKey('last_update', $response[0], 'First user should have "last_update" key');
            $this->assertArrayHasKey('system_user', $response[0], 'First user should have "system_user" key');
            $this->assertArrayHasKey('admin', $response[0], 'First user should have "admin" key');
            $this->assertArrayHasKey('color', $response[0], 'First user should have "color" key');
        }
    }
}
