<?php

namespace Charles\MessageBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AggregControllerTest extends WebTestCase
{
    public function testAggregWithIncorrectData()
    {
        $data = [
            "type" => "text",
            "to" => "33644630246",
            "msisdn" => "myIdentifier",
            "messageId" => "000000FFFB0356D1",
            'text' => null,
            'message-timestamp' => '2012-08-19+20%3A38%3A23',
        ];

        $client = static::createClient([], ['HTTP_HOST' => "api.charles.dev"]);
        $client->request('GET', '/api/1/messages/aggreg', $data, [], ['CONTENT_TYPE' => 'application/json', 'HTTP_ACCEPT'  => 'application/json']);

        $this->assertEquals(400, $client->getResponse()->getStatusCode());
        $this->assertEquals('', json_decode($client->getResponse()->getContent()));
    }

    public function testAggregWithUserAssociated()
    {
        $data = [
            "type" => "text",
            "to" => "33644630246",
            "msisdn" => "testIdentifier",
            "messageId" => "000000FFFB0356D1",
            'text' => 'foo',
            'message-timestamp' => '2012-08-19+20%3A38%3A23',
        ];

        $client = static::createClient([], ['HTTP_HOST' => "api.charles.dev"]);
        $client->request('GET', '/api/1/messages/aggreg', $data, [], ['CONTENT_TYPE' => 'application/json', 'HTTP_ACCEPT'  => 'application/json']);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals('', json_decode($client->getResponse()->getContent()));
    }

    public function testAggregWithIdentifierUnknown()
    {
        $data = [
            "type" => "text",
            "to" => "33644630246",
            "msisdn" => "newIdentifier",
            "messageId" => "000000FFFB0356D1",
            'text' => 'foo',
            'message-timestamp' => '2012-08-19+20%3A38%3A23',
        ];

        $client = static::createClient([], ['HTTP_HOST' => "api.charles.dev"]);
        $client->request('GET', '/api/1/messages/aggreg', $data, [], ['CONTENT_TYPE' => 'application/json', 'HTTP_ACCEPT'  => 'application/json']);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals('', json_decode($client->getResponse()->getContent()));
    }
}
