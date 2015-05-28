<?php

namespace Charles\MessageBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class MessageControllerTest extends WebTestCase
{
    public function testCreateMessageWithUserNotFound()
    {
        $data = [
            "content" => "An admin answer",
            "source" => "app",
        ];

        $client = static::createClient([], ['HTTP_HOST' => "api.charles.dev"]);
        $client->request('POST', '/api/1/users/99999999999999999999/messages', [], [], [
            'CONTENT_TYPE' => 'application/json',
            'HTTP_ACCEPT'  => 'application/json',
            'HTTP_TOKEN' => 'testToken',
        ], json_encode($data));

        $content = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }

    public function testCreateMessageWithInvalidData()
    {
        $data = [
            "content" => null,
            "source" => "app",
        ];

        $client = static::createClient([], ['HTTP_HOST' => "api.charles.dev"]);
        $client->request('POST', '/api/1/users/2/messages', [], [], [
            'CONTENT_TYPE' => 'application/json',
            'HTTP_ACCEPT'  => 'application/json',
            'HTTP_TOKEN' => 'testToken',
        ], json_encode($data));

        $content = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals(400, $client->getResponse()->getStatusCode());
        $this->assertEquals('Validation Failed', $content['message']);
    }

    public function testCreateMessage()
    {
        $data = [
            "content" => "My answer",
            "source" => "app",
        ];

        $client = static::createClient([], ['HTTP_HOST' => "api.charles.dev"]);
        $client->request('POST', '/api/1/users/2/messages', [], [], [
            'CONTENT_TYPE' => 'application/json',
            'HTTP_ACCEPT'  => 'application/json',
            'HTTP_TOKEN' => 'testToken',
        ], json_encode($data));

        $content = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals(201, $client->getResponse()->getStatusCode());
        $this->assertEquals('My answer', $content['content']);
    }

    public function testgetMessageOfUnknownUser()
    {
        $client = static::createClient([], ['HTTP_HOST' => "api.charles.dev"]);
        $client->request('GET', '/api/1/users/9999999999/messages', [], [], [
            'CONTENT_TYPE' => 'application/json',
            'HTTP_ACCEPT'  => 'application/json',
            'HTTP_TOKEN' => 'testGetAdminMessagesToken',
        ]);

        $content = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }

    public function testgetMessage()
    {
        $client = static::createClient([], ['HTTP_HOST' => "api.charles.dev"]);
        $client->request('GET', '/api/1/users/4/messages', [], [], [
            'CONTENT_TYPE' => 'application/json',
            'HTTP_ACCEPT'  => 'application/json',
            'HTTP_TOKEN' => 'testGetAdminMessagesToken',
        ]);

        $content = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertCount(2, $content);
        $this->assertEquals('My question', $content[0]['content']);
        $this->assertEquals('testUserMessages@charles.com', $content[0]['author']['email']);
        $this->assertArrayNotHasKey('replyTo', $content[0]);
        $this->assertEquals('My answer', $content[1]['content']);
        $this->assertEquals('testGetAdminMessages@charles.com', $content[1]['author']['email']);
        $this->assertEquals('testUserMessages@charles.com', $content[1]['reply_to']['email']);
    }
}
