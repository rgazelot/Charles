<?php

namespace Charles\ApiBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ApiControllerTest extends WebTestCase
{
    public function testAuthenticationWithoutEmail()
    {
        $data = [
            "password" => "hoho",
        ];

        $client = static::createClient([], ['HTTP_HOST' => "api.charles.dev"]);
        $client->request('POST', '/api/1/authentications', [], [], ['CONTENT_TYPE' => 'application/json', 'HTTP_ACCEPT'  => 'application/json'], json_encode($data));

        $content = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals(400, $client->getResponse()->getStatusCode());
        $this->assertEquals('invalid_parameters', $content['code']);
        $this->assertEquals('email required', $content['message']);
    }

    public function testAuthenticationWithoutSecret()
    {
        $data = [
            "email" => "hoho",
        ];

        $client = static::createClient([], ['HTTP_HOST' => "api.charles.dev"]);
        $client->request('POST', '/api/1/authentications', [], [], ['CONTENT_TYPE' => 'application/json', 'HTTP_ACCEPT'  => 'application/json'], json_encode($data));

        $content = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals(400, $client->getResponse()->getStatusCode());
        $this->assertEquals('invalid_parameters', $content['code']);
        $this->assertEquals('password required', $content['message']);
    }

    public function testAuthenticationWithEmailNotFound()
    {
        $data = [
            "email" => "hoho",
            "password" => "hoho",
        ];

        $client = static::createClient([], ['HTTP_HOST' => "api.charles.dev"]);
        $client->request('POST', '/api/1/authentications', [], [], ['CONTENT_TYPE' => 'application/json', 'HTTP_ACCEPT'  => 'application/json'], json_encode($data));

        $content = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals(400, $client->getResponse()->getStatusCode());
        $this->assertEquals('invalid_credentials', $content['code']);
        $this->assertEquals('Invalid credentials', $content['message']);
    }

    public function testAuthenticationWithWrongPassword()
    {
        $data = [
            "email" => "test@charles.com",
            "password" => "foo",
        ];

        $client = static::createClient([], ['HTTP_HOST' => "api.charles.dev"]);
        $client->request('POST', '/api/1/authentications', [], [], ['CONTENT_TYPE' => 'application/json', 'HTTP_ACCEPT'  => 'application/json'], json_encode($data));

        $content = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals(400, $client->getResponse()->getStatusCode());
        $this->assertEquals('invalid_credentials', $content['code']);
        $this->assertEquals('Invalid credentials', $content['message']);
    }

    public function testAuthentication()
    {
        $data = [
            "email" => "test@charles.com",
            "password" => "pass",
        ];

        $client = static::createClient([], ['HTTP_HOST' => "api.charles.dev"]);
        $client->request('POST', '/api/1/authentications', [], [], ['CONTENT_TYPE' => 'application/json', 'HTTP_ACCEPT'  => 'application/json'], json_encode($data));

        $content = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals('test@charles.com', $content['email']);
        $this->assertEquals('testToken', $content['token']);
        $this->assertArrayNotHasKey('password', $content);
    }
}
