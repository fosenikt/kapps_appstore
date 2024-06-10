<?php

require_once __DIR__ . '/../BaseTestCase.php';

class CreateAndDeleteTest extends BaseTestCase
{
    public function testCreateAndDeleteUser()
    {
        // Authenticate and get token
        $this->authenticate();

        // Step 1: Create a new user
        $createData = [
            'firstname' => 'John',
            'lastname' => 'Doe',
            'mail' => 'john.doe@example.com',
            'mobile' => '12345678',
            'company_role' => 'Manager',
            'status' => 'active',
            'customer_id' => 1,
            'admin' => 1,
        ];
        $createResponse = $this->post('/admin/user/create', $createData);

        // Ensure the response is not empty and has success status
        $this->assertNotEmpty($createResponse, 'Create response should not be empty');
        $this->assertEquals('success', $createResponse['status'], 'Create response status should be success');

        // Ensure the response contains the user ID
        $this->assertArrayHasKey('id', $createResponse, 'Create response should contain user ID');
        $userId = $createResponse['id'];

        // Step 2: Delete the created user
        $deleteResponse = $this->delete('/admin/user/delete/' . $userId);

        // Ensure the delete response is not empty and has success status
        $this->assertNotEmpty($deleteResponse, 'Delete response should not be empty');
        $this->assertEquals('success', $deleteResponse['status'], 'Delete response status should be success');
    }
}
