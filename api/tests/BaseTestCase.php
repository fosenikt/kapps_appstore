<?php

use PHPUnit\Framework\TestCase;

class BaseTestCase extends TestCase
{
    protected $baseUrl = 'http://kapps-appstore-api'; // Docker service name for the API
    protected $authToken;

    public function setUp(): void
    {
        require_once __DIR__ . '/../src/config.php'; // Ensure configuration is loaded
        require_once __DIR__ . '/../src/app/Model/Database/Db.php'; // Ensure Db class is loaded
    }

    protected function get($uri)
    {
        return $this->request('GET', $uri);
    }

    protected function post($uri, $data = [], $isMultipart = false)
    {
        return $this->request('POST', $uri, $data, $isMultipart);
    }

    protected function delete($uri)
    {
        return $this->request('DELETE', $uri);
    }

    protected function setAuthToken($token)
    {
        $this->authToken = $token;
    }

    protected function request($method, $uri, $data = [], $isMultipart = false)
    {
        $url = $this->baseUrl . $uri;
        $headers = $isMultipart ? "Content-Type: multipart/form-data\r\n" : "Content-Type: application/json\r\n";
        if ($this->authToken) {
            $headers .= "Authorization: Bearer {$this->authToken}\r\n";
        }
        $options = [
            'http' => [
                'header' => $headers,
                'method' => $method,
                'content' => $isMultipart ? http_build_query($data) : json_encode($data),
                'ignore_errors' => true
            ]
        ];
        $context = stream_context_create($options);
        $response = file_get_contents($url, false, $context);
        return json_decode($response, true);
    }

    protected function getDbConnection()
    {
        return \Kapps\Model\Database\Db::getInstance();
    }

    protected function authenticate()
    {
        // Step 1: Send login link
        $mail = 'test.user@example.com';
        $sendLoginLinkResponse = $this->post('/auth/login/send', ['mail' => $mail]);

        // Ensure the response is not empty and has success status
        $this->assertNotEmpty($sendLoginLinkResponse, 'Send login link response should not be empty');
        $this->assertEquals('success', $sendLoginLinkResponse['status'], 'Send login link response status should be success');

        // Get the code from the database
        $code = $this->getLoginCodeForTesting($mail);

        // Step 2: Validate code to get token
        $validateCodeResponse = $this->post('/auth/login/validate/code', ['mail' => $mail, 'code' => $code]);

        // Ensure the response is not empty and has success status
        $this->assertNotEmpty($validateCodeResponse, 'Validate code response should not be empty');
        $this->assertEquals('success', $validateCodeResponse['status'], 'Validate code response status should be success');

        // Set the auth token
        $this->setAuthToken($validateCodeResponse['token']);
    }

    protected function getLoginCodeForTesting($mail)
    {
        $db = $this->getDbConnection();

        if ($stmt = $db->prepare('SELECT pincode FROM login_keys WHERE user_id = (SELECT id FROM users WHERE mail = ?) ORDER BY time_expires DESC LIMIT 1')) {
            $stmt->bind_param('s', $mail);
            $stmt->execute();
            $stmt->bind_result($code);
            $stmt->fetch();
            $stmt->close();
            return $code;
        }

        return null;
    }
}
