<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AuthControllerTest extends WebTestCase
{
    private static string $uniqueEmail;
    private static string $authToken;
    private static $client;

    public static function setUpBeforeClass(): void
    {
        self::$client = static::createClient();
        self::$uniqueEmail = 'user_' . uniqid() . '@example.com';
    }

    public function testRegister(): void
    {
        self::$client->request('POST', '/api/register', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'email' => self::$uniqueEmail,
            'password' => '12345',
        ]));

        $response = self::$client->getResponse();
        $this->assertEquals(201, $response->getStatusCode());

    }

    public function testLogin(): void
    {
        self::$client->request('POST', '/api/login', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'email' => self::$uniqueEmail,
            'password' => '12345',
        ]));

        $response = self::$client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());

        $responseData = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('token', $responseData['result']);

        $responseData = json_decode($response->getContent(), true);
        self::$authToken = $responseData['result']['token'] ?? '';
    }

    // public function testLogout(): void
    // {
    //     self::$client->request('POST', '/api/logout', [], [], [
    //         'CONTENT_TYPE' => 'application/json',
    //         'HTTP_AUTHORIZATION' => 'Bearer ' . self::$authToken
    //     ]);

    //     $response = self::$client->getResponse();
    //     $this->assertEquals(200, $response->getStatusCode());
    // }

    public function testInvalidLogin(): void
    {
        self::$client->request('POST', '/api/login', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'email' => 'wronguser@example.com',
            'password' => 'wrongpassword',
        ]));

        $response = self::$client->getResponse();
        $this->assertEquals(401, $response->getStatusCode());
    }

}
