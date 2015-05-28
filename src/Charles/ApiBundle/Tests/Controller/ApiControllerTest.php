<?php

namespace Charles\ApiBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ApiControllerTest extends WebTestCase
{
    public function testGetAuthenticationWithUnknownToken()
    {
        $client = static::createClient([], ['HTTP_HOST' => "api.charles.dev"]);
        $client->request('GET', '/api/1/authentications', ['token' => 'foooooooo'], [], ['CONTENT_TYPE' => 'application/json', 'HTTP_ACCEPT'  => 'application/json']);

        $content = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals(404, $client->getResponse()->getStatusCode());
        $this->assertEquals('Not Found', $content['error']['message']);
    }

    public function testGetAuthenticationWithoutToken()
    {
        $client = static::createClient([], ['HTTP_HOST' => "api.charles.dev"]);
        $client->request('GET', '/api/1/authentications', [], [], ['CONTENT_TYPE' => 'application/json', 'HTTP_ACCEPT'  => 'application/json']);

        $content = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals(400, $client->getResponse()->getStatusCode());
        $this->assertEquals('Bad Request', $content['error']['message']);
    }

    public function testGetAuthentication()
    {
        $client = static::createClient([], ['HTTP_HOST' => "api.charles.dev"]);
        $client->request('GET', '/api/1/authentications', ['token' => 'testToken'], [], ['CONTENT_TYPE' => 'application/json', 'HTTP_ACCEPT'  => 'application/json']);

        $content = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals('test@charles.com', $content['email']);
        $this->assertEquals('testToken', $content['token']);
        $this->assertArrayNotHasKey('password', $content);
    }

    public function testAuthenticationWithoutEmail()
    {
        $data = [
            "password" => "hoho",
        ];

        $client = static::createClient([], ['HTTP_HOST' => "api.charles.dev"]);
        $client->request('POST', '/api/1/authentications', [], [], ['CONTENT_TYPE' => 'application/json', 'HTTP_ACCEPT'  => 'application/json'], json_encode($data));

        $content = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals(400, $client->getResponse()->getStatusCode());
        $this->assertEquals('invalid_parameters', $content['error']['code']);
        $this->assertEquals('email required', $content['error']['message']);
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
        $this->assertEquals('invalid_parameters', $content['error']['code']);
        $this->assertEquals('password required', $content['error']['message']);
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
        $this->assertEquals('invalid_credentials', $content['error']['code']);
        $this->assertEquals('Invalid credentials', $content['error']['message']);
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
        $this->assertEquals('invalid_credentials', $content['error']['code']);
        $this->assertEquals('Invalid credentials', $content['error']['message']);
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
