<?php

namespace Charles\MessageBundle\EventListener;

use Exception;

use GuzzleHttp\ClientInterface as Guzzle;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class NexmoSubscriber implements EventSubscriberInterface
{
    private $guzzle;
    private $nexmoApiKey;
    private $nexmoApiSecret;

    public function __construct(Guzzle $guzzle, $nexmoApiKey = null, $nexmoApiSecret = null)
    {
        $this->guzzle = $guzzle;
        $this->nexmoApiKey = $nexmoApiKey;
        $this->nexmoApiSecret = $nexmoApiSecret;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            MessageEvents::MESSAGE_CREATED => 'onMessageCreated',
        ];
    }

    public function onMessageCreated(MessageEvent $event)
    {
        if (null === $this->nexmoApiKey || null === $this->nexmoApiSecret) {
            return;
        }

        $message = $event->getMessage();

        $options = [
            'headers' => ['Content-Type' => 'application/x-www-form-urlencoded', 'Accept' => 'application/json'],
            'query'   => [
                'api_key' => $this->nexmoApiKey,
                'api_secret' => $this->nexmoApiSecret,
                'from' => 'Charles',
                'to' => $message->getReplyTo()->getIdentifier(),
                'type' => 'text',
                'text' => $message->getContent(),
            ],
        ];

        try {
            $clientRequest = $this->guzzle->createRequest(
                'POST',
                'https://rest.nexmo.com/sms/json',
                $options
            );

            return $this->guzzle->send($clientRequest);
            // Catch all Guzzle\Request exceptions
        } catch (Exception $e) {

        }
    }
}
