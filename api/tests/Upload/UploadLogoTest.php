<?php

/* require_once __DIR__ . '/../BaseTestCase.php';

class UploadLogoTest extends BaseTestCase
{
    public function testUploadCompanyLogo()
    {
        // Authenticate and get token
        $this->authenticate();

        // Prepare the data for the upload
        $postData = [
            'public_id' => 'example-public-id'
        ];

        // Prepare the file for the upload
        $fileData = [
            'image' => new CURLFile(realpath('path/to/logo.png'))
        ];

        // Step 1: Upload the company logo
        $response = $this->post('/company/logo/upload', $postData, $fileData);

        // Ensure the response is not empty
        $this->assertNotEmpty($response, 'Response should not be empty');

        // Ensure the response has success status
        $this->assertEquals('success', $response['status'], 'Upload response status should be success');
    }
}
 */