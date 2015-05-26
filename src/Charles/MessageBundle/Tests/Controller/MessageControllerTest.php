<?php

namespace Charles\MessageBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class MessageControllerTest extends WebTestCase
{
    public function testCreateMessage()
    {
        $data = [
            "content" => "My content",
            "source" => "app",
        ];

        $client = static::createClient([], ['HTTP_HOST' => "api.charles.dev"]);
        $client->request('POST', '/api/1/messages', $data, [], [
            'CONTENT_TYPE' => 'application/json',
            'HTTP_ACCEPT'  => 'application/json',
            'HTTP_TOKEN' => 'myToken',
        ], json_encode($data));

        $data = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals(201, $client->getResponse()->getStatusCode());
        $this->assertArrayHasKey('content', $data);
        $this->assertEquals('My content', $data['content']);
        $this->assertArrayHasKey('source', $data);
        $this->assertEquals('app', $data['source']);
    }

    public function testAggregWithUserMessage()
    {
        $data = [
            "type" => "text",
            "to" => "33644630246",
            "msisdn" => "myIdentifier",
            "messageId" => "000000FFFB0356D1",
            'text' => 'This+is+an+inbound+message',
            'message-timestamp' => '2012-08-19+20%3A38%3A23',
        ];

        $client = static::createClient([], ['HTTP_HOST' => "api.charles.dev"]);
        $client->request('GET', '/api/1/messages/aggreg', $data, [], ['CONTENT_TYPE' => 'application/json', 'HTTP_ACCEPT'  => 'application/json']);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals('', json_decode($client->getResponse()->getContent()));
    }

    public function testAggregWithoutUserMessage()
    {
        $data = [
            "type" => "text",
            "to" => "33644630246",
            "msisdn" => "jduERTopdu",
            "messageId" => "000000FFFB0356D1",
            'text' => 'This+is+an+inbound+message',
            'message-timestamp' => '2012-08-19+20%3A38%3A23',
        ];

        $client = static::createClient([], ['HTTP_HOST' => "api.charles.dev"]);
        $client->request('GET', '/api/1/messages/aggreg', $data, [], ['CONTENT_TYPE' => 'application/json', 'HTTP_ACCEPT'  => 'application/json']);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals('', json_decode($client->getResponse()->getContent()));
    }
}
