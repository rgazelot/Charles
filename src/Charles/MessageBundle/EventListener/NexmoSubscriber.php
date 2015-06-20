<?php

namespace Charles\MessageBundle\EventListener;

use Exception;

use Psr\Log\LoggerInterface,
    Psr\Log\NullLogger;

use GuzzleHttp\ClientInterface as Guzzle;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

use Charles\UserBundle\EventListener\UserEvents,
    Charles\UserBundle\EventListener\UserEvent;

class NexmoSubscriber implements EventSubscriberInterface
{
    private $guzzle;
    private $nexmoApiNumber;
    private $nexmoApiKey;
    private $nexmoApiSecret;
    private $logger;

    public function __construct(Guzzle $guzzle, $nexmoApiNumber = null, $nexmoApiKey = null, $nexmoApiSecret = null, LoggerInterface $logger = null)
    {
        $this->guzzle = $guzzle;
        $this->nexmoApiNumber = $nexmoApiNumber;
        $this->nexmoApiKey = $nexmoApiKey;
        $this->nexmoApiSecret = $nexmoApiSecret;
        $this->logger = null !== $logger ? $logger : new NullLogger;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            MessageEvents::MESSAGE_CREATED => 'onMessageCreated',
            UserEvents::USER_CREATED => 'onUserCreated',
        ];
    }

    public function onMessageCreated(MessageEvent $event)
    {
        if ('sms' === $event->getMessage() || null === $this->nexmoApiNumber || null === $this->nexmoApiKey || null === $this->nexmoApiSecret) {
            return;
        }

        $message = $event->getMessage();

        $options = [
            'headers' => ['Content-Type' => 'application/x-www-form-urlencoded', 'Accept' => 'application/json'],
            'query'   => [
                'api_key' => $this->nexmoApiKey,
                'api_secret' => $this->nexmoApiSecret,
                'from' => $this->nexmoApiNumber,
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

            $response =  $this->guzzle->send($clientRequest);
            $this->logger->info('message_sent', ['response' => $response->json()]);

            // Catch all Guzzle\Request exceptions
        } catch (Exception $e) {
            $this->logger->info('message_not_sent');
        }
    }

    public function onUserCreated(UserEvent $event)
    {
        if (null === $this->nexmoApiNumber || null === $this->nexmoApiKey || null === $this->nexmoApiSecret) {
            return;
        }

        $user = $event->getUser();
        $text = "Bonjour, Je suis Charles votre nouvel assistant personnel. Afin de faciliter l'utilisation de notre service, merci de bien vouloir compléter notre formulaire d’inscription à cette adresse : http://google.fr";

        $options = [
            'headers' => ['Content-Type' => 'application/x-www-form-urlencoded', 'Accept' => 'application/json'],
            'query'   => [
                'api_key' => $this->nexmoApiKey,
                'api_secret' => $this->nexmoApiSecret,
                'from' => $this->nexmoApiNumber,
                'to' => $user->getIdentifier(),
                'type' => 'text',
                'text' => $text,
            ],
        ];

        try {
            $clientRequest = $this->guzzle->createRequest(
                'POST',
                'https://rest.nexmo.com/sms/json',
                $options
            );

            $response = $this->guzzle->send($clientRequest);
            $this->logger->info('message_sent', ['response' => $response->json()]);

            // Catch all Guzzle\Request exceptions
        } catch (Exception $e) {
            $this->logger->info('message_not_sent');
        }
    }
}
