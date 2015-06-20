<?php

namespace Charles\MessageBundle\EventListener;

use Doctrine\ORM\EntityManager;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class UpdateLastMessageSubscriber implements EventSubscriberInterface
{
    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
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
        $message = $event->getMessage();

        if ('app' === $message->getSource()) {
            return;
        }

        $author = $message->getAuthor();
        $author->setLastMessage($message->getCreatedAt());

        $this->em->persist($author);
        $this->em->flush();
    }
}
