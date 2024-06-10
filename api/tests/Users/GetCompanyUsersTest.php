<?php

require_once __DIR__ . '/../BaseTestCase.php';

class GetCompanyUsersTest extends BaseTestCase
{
    public function testGetCompanyUsers()
    {
        // Authenticate and get token
        $this->authenticate();

        // Assuming there is a company with ID 1
        $companyId = 'o4yEbqLm';

        // Step 1: Get all users for the company
        $response = $this->get('/users/company/get/' . $companyId);

        // Ensure the response is not empty
        $this->assertNotEmpty($response, 'Response should not be empty');

        // Ensure the response is an array
        $this->assertIsArray($response, 'Response should be an array');

        // Ensure the first item in the response contains the expected keys
        if (isset($response[0])) {
            $this->assertArrayHasKey('id', $response[0], 'First user should have "id" key');
            $this->assertArrayHasKey('firstname', $response[0], 'First user should have "firstname" key');
            $this->assertArrayHasKey('lastname', $response[0], 'First user should have "lastname" key');
            $this->assertArrayHasKey('initials', $response[0], 'First user should have "initials" key');
            $this->assertArrayHasKey('mail', $response[0], 'First user should have "mail" key');
            $this->assertArrayHasKey('mobile', $response[0], 'First user should have "mobile" key');
            $this->assertArrayHasKey('photo', $response[0], 'First user should have "photo" key');
            $this->assertArrayHasKey('company_role', $response[0], 'First user should have "company_role" key');
            $this->assertArrayHasKey('color', $response[0], 'First user should have "color" key');
        }
    }
}
