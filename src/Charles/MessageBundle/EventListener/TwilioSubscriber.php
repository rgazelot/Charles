<?php

namespace Charles\MessageBundle\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

use Charles\MessageBundle\Exception\TwilioException,
    Charles\MessageBundle\Service\Provider\Twilio,
    Charles\UserBundle\EventListener\UserEvents,
    Charles\UserBundle\EventListener\UserEvent;

class TwilioSubscriber implements EventSubscriberInterface
{
    private $twilio;

    public function __construct(Twilio $twilio)
    {
        $this->twilio = $twilio;
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
        $message = $event->getMessage();

        if (false === $this->twilio->isActive() || 'sms' === $message->getSource()) {
            return;
        }

        try {
            $this->twilio->create('+33676781891', 'test');
        } catch(TwilioException $e) {

        }
    }

    public function onUserCreated(UserEvent $event)
    {
        if (false === $this->twilio->isActive()) {
            return;
        }

        $user = $event->getUser();

        switch($user->getVia())
        {
            case 'sms':
                $text = sprintf("Bonjour, Je suis Charles votre nouvel assistant personnel. Afin de faciliter l'utilisation de notre service, merci de bien vouloir compléter notre formulaire d’inscription à cette adresse : http://merci-charles.fr/inscription.html?phone=%s", $user->getPhone());

                break;
            case 'web':
                $text = "Bonjour";

                break;
        }

        try {
            $this->twilio->create($user->getPhone(), $text);
        } catch(TwilioException $e) {

        }
    }
}
