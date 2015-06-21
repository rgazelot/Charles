<?php

namespace Charles\MessageBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AggregControllerTest extends WebTestCase
{
    public function testAggregWithIncorrectData()
    {
        $data = json_decode('{"ToCountry":"FR","ToState":"","SmsMessageSid":"SMafb1ce29f0527657149298898b0ccc7c","NumMedia":"0","ToCity":"","FromZip":"","SmsSid":"SMafb1ce29f0527657149298898b0ccc7c","FromState":"","SmsStatus":"received","FromCity":"","Body":null,"FromCountry":"FR","To":"+33644600304","ToZip":"","MessageSid":"SMafb1ce29f0527657149298898b0ccc7c","AccountSid":"AC40abbc861a9059ab550a22a03a00adfc","From":"+33601010101","ApiVersion":"2010-04-01"}', true);

        $client = static::createClient([], ['HTTP_HOST' => "api.charles.dev"]);
        $client->request('POST', '/api/1/messages/twilios', [], [], [
            'CONTENT_TYPE' => 'application/json',
            'HTTP_ACCEPT'  => 'application/json',
            'HTTP_TOKEN' => 'testToken',
        ], json_encode($data));

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals('', json_decode($client->getResponse()->getContent()));
    }

    public function testAggregWithUserAssociated()
    {
        $data = json_decode('{"ToCountry":"FR","ToState":"","SmsMessageSid":"SMafb1ce29f0527657149298898b0ccc7c","NumMedia":"0","ToCity":"","FromZip":"","SmsSid":"SMafb1ce29f0527657149298898b0ccc7c","FromState":"","SmsStatus":"received","FromCity":"","Body":"Test Aggreg","FromCountry":"FR","To":"+33644600304","ToZip":"","MessageSid":"SMafb1ce29f0527657149298898b0ccc7c","AccountSid":"AC40abbc861a9059ab550a22a03a00adfc","From":"+33601010101","ApiVersion":"2010-04-01"}', true);

        $client = static::createClient([], ['HTTP_HOST' => "api.charles.dev"]);
        $client->request('POST', '/api/1/messages/twilios', [], [], [
            'CONTENT_TYPE' => 'application/json',
            'HTTP_ACCEPT'  => 'application/json',
            'HTTP_TOKEN' => 'testToken',
        ], json_encode($data));

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals('', json_decode($client->getResponse()->getContent()));
    }

    public function testAggregWithIdentifierUnknown()
    {
        $data = json_decode('{"ToCountry":"FR","ToState":"","SmsMessageSid":"SMafb1ce29f0527657149298898b0ccc7c","NumMedia":"0","ToCity":"","FromZip":"","SmsSid":"SMafb1ce29f0527657149298898b0ccc7c","FromState":"","SmsStatus":"received","FromCity":"","Body":"Test Aggreg","FromCountry":"FR","To":"+33644600304","ToZip":"","MessageSid":"SMafb1ce29f0527657149298898b0ccc7c","AccountSid":"AC40abbc861a9059ab550a22a03a00adfc","From":"+33601010199","ApiVersion":"2010-04-01"}', true);

        $client = static::createClient([], ['HTTP_HOST' => "api.charles.dev"]);
        $client->request('POST', '/api/1/messages/twilios', [], [], [
            'CONTENT_TYPE' => 'application/json',
            'HTTP_ACCEPT'  => 'application/json',
            'HTTP_TOKEN' => 'testToken',
        ], json_encode($data));

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals('', json_decode($client->getResponse()->getContent()));
    }
}
