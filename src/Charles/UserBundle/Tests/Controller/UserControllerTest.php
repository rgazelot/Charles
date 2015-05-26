<?php

namespace Charles\UserBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $data = [
            "email" => "hehe@charles.com",
            "phone" => "0602020202",
            "password" => "hoho",
        ];

        $client = static::createClient([], ['HTTP_HOST' => "api.charles.dev"]);
        $client->request('POST', '/api/1/users', [], [], ['CONTENT_TYPE' => 'application/json', 'HTTP_ACCEPT'  => 'application/json'], json_encode($data));

        $content = json_decode($client->getResponse()->getContent(), true);
    }
}
