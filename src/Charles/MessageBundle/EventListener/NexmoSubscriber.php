<?php

namespace Charles\MessageBundle\EventListener;

use Exception;

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

    public function __construct(Guzzle $guzzle, $nexmoApiNumber = null, $nexmoApiKey = null, $nexmoApiSecret = null)
    {
        $this->guzzle = $guzzle;
        $this->nexmoApiNumber = $nexmoApiNumber;
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
            UserEvents::USER_CREATED => 'onUserCreated',
        ];
    }

    public function onMessageCreated(MessageEvent $event)
    {
        if (null === $this->nexmoApiNumber || null === $this->nexmoApiKey || null === $this->nexmoApiSecret) {
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

            return $this->guzzle->send($clientRequest);
            // Catch all Guzzle\Request exceptions
        } catch (Exception $e) {

        }
    }

    public function onUserCreated(UserEvent $event)
    {
        if (null === $this->nexmoApiNumber || null === $this->nexmoApiKey || null === $this->nexmoApiSecret) {
            return;
        }

        $user = $event->getUser();

        $options = [
            'headers' => ['Content-Type' => 'application/x-www-form-urlencoded', 'Accept' => 'application/json'],
            'query'   => [
                'api_key' => $this->nexmoApiKey,
                'api_secret' => $this->nexmoApiSecret,
                'from' => $this->nexmoApiNumber,
                'to' => $user->getIdentifier(),
                'type' => 'text',
                'text' => "Bonjour, Je suis Charles votre nouvel assistant personnel. Afin de faciliter l’utilisation de notre service, merci de bien vouloir compléter notre formulaire d’inscription à cette adresse : http://google.fr",
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
