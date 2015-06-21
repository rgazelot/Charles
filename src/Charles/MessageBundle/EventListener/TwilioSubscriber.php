<?php

namespace Charles\MessageBundle\EventListener;

use Doctrine\ORM\EntityManager;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

use Charles\MessageBundle\Exception\TwilioException,
    Charles\MessageBundle\Service\Provider\Twilio,
    Charles\MessageBundle\Service\Message,
    Charles\UserBundle\EventListener\UserEvents,
    Charles\UserBundle\EventListener\UserEvent;

class TwilioSubscriber implements EventSubscriberInterface
{
    private $twilio;
    private $em;

    public function __construct(Twilio $twilio, EntityManager $em, Message $messageApi)
    {
        $this->twilio = $twilio;
        $this->em = $em;
        $this->messageApi = $messageApi;
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
            $return = $this->twilio->create($message->getReplyTo()->getPhone(), $message->getContent());
        } catch(TwilioException $e) {
            return;
        }

        $message->setProviderId($return->sid);

        $this->em->persist($message);
        $this->em->flush();
    }

    public function onUserCreated(UserEvent $event)
    {
        if (false === $this->twilio->isActive()) {
            return;
        }

        $user = $event->getUser();
        $charles = $this->em->getRepository('CharlesUserBundle:User')->findByEmail('welcome@merci-charles.fr');

        if (null === $charles) {
            return;
        }

        switch($user->getVia())
        {
            case 'sms':
                $text = sprintf("Bonjour, Je suis Charles votre nouvel assistant personnel. Afin de faciliter l'utilisation de notre service, merci de bien vouloir compléter notre formulaire d’inscription à cette adresse : http://merci-charles.fr/inscription.html?phone=%s", $user->getPhone());

                break;
            case 'web':
                $text = sprintf("Bonjour %s, je suis Charles, votre nouvel assistant personnel. Je vous remercie de m’accorder votre confiance pour vous accompagner au quotidien. Je suis à votre disposition gratuitement pendant 15 jours, n’hésitez pas à me solliciter. A bientôt, Charles.", $user->getFirstname());

                break;
        }

        $message = $this->messageApi->create(['content' => $text], $charles, $user, 'app');

        try {
            $return = $this->twilio->create($user->getPhone(), $message->getContent());
        } catch(TwilioException $e) {
            return;
        }

        $message->setProviderId($return->sid);

        $this->em->persist($message);
        $this->em->flush();
    }
}
