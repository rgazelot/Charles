<?php

namespace Charles\UserBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{
    public function testGetUnknownUser()
    {
        $client = static::createClient([], ['HTTP_HOST' => "api.charles.dev"]);
        $client->request('GET', '/api/1/users/9999999999', ['token' => 'testToken'], [], ['CONTENT_TYPE' => 'application/json', 'HTTP_ACCEPT'  => 'application/json']);

        $content = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals(404, $client->getResponse()->getStatusCode());
        $this->assertEquals('user_not_found', $content['error']['code']);
    }

    public function testGetUser()
    {
        $client = static::createClient([], ['HTTP_HOST' => "api.charles.dev"]);
        $client->request('GET', '/api/1/users/1', ['token' => 'testToken'], [], ['CONTENT_TYPE' => 'application/json', 'HTTP_ACCEPT'  => 'application/json']);

        $content = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals('test@charles.com', $content['email']);
        $this->assertArrayNotHasKey('password', $content);
    }

    public function testPostUserWithWrongData()
    {
        $data = [
            'email' => 'foo',
            'password' => '123456'
        ];

        $client = static::createClient([], ['HTTP_HOST' => "api.charles.dev"]);
        $client->request('POST', '/api/1/users', ['token' => 'testToken'], [], ['CONTENT_TYPE' => 'application/json', 'HTTP_ACCEPT'  => 'application/json'], json_encode($data));

        $content = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals(400, $client->getResponse()->getStatusCode());
        $this->assertEquals('Validation Failed', $content['message']);
    }

    public function testPostUserWithEmailAlreadyUsed()
    {
        $data = [
            'email' => 'test@charles.com',
            'password' => '123456'
        ];

        $client = static::createClient([], ['HTTP_HOST' => "api.charles.dev"]);
        $client->request('POST', '/api/1/users', ['token' => 'testToken'], [], ['CONTENT_TYPE' => 'application/json', 'HTTP_ACCEPT'  => 'application/json'], json_encode($data));

        $content = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals(409, $client->getResponse()->getStatusCode());
    }

    public function testPostUser()
    {
        $data = [
            'email' => 'foo@charles.com',
            'password' => '123456'
        ];

        $client = static::createClient([], ['HTTP_HOST' => "api.charles.dev"]);
        $client->request('POST', '/api/1/users', ['token' => 'testToken'], [], ['CONTENT_TYPE' => 'application/json', 'HTTP_ACCEPT'  => 'application/json'], json_encode($data));

        $content = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals(201, $client->getResponse()->getStatusCode());
        $this->assertEquals('foo@charles.com', $content['email']);
    }

    public function testGetUsers()
    {
        $client = static::createClient([], ['HTTP_HOST' => "api.charles.dev"]);
        $client->request('GET', '/api/1/users', ['token' => 'testToken'], [], ['CONTENT_TYPE' => 'application/json', 'HTTP_ACCEPT'  => 'application/json']);

        $content = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals('test@charles.com', $content[0]['email']);
    }
}
