<?php

namespace Charles\MessageBundle\DataFixtures\ORM;

use DateTime,
    DateTimeZone;

use Doctrine\Common\DataFixtures\AbstractFixture,
    Doctrine\Common\DataFixtures\OrderedFixtureInterface,
    Doctrine\Common\Persistence\ObjectManager;

use Charles\MessageBundle\Entity\Message;

class LoadMessageData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $author = $this->getReference('user.create_messages');
        $admin = $this->getReference('user.get_messages.admin');
        $user = $this->getReference('user.get_messages.user');

        $message = new Message;
        $message->setAuthor($author);
        $message->setContent('My question');
        $message->setSource('sms');
        $message->setStatus(Message::STATUS_DELIVRED);
        $message->setCreatedAt(new DateTime('2015-01-01', new DateTimeZone('UTC')));
        $manager->persist($message);

        $message = new Message;
        $message->setAuthor($user);
        $message->setContent('My question');
        $message->setSource('sms');
        $message->setStatus(Message::STATUS_DELIVRED);
        $message->setCreatedAt(new DateTime('2015-01-01T00:00:00', new DateTimeZone('UTC')));
        $manager->persist($message);

        $message = new Message;
        $message->setAuthor($admin);
        $message->setReplyTo($user);
        $message->setContent('My answer');
        $message->setSource('app');
        $message->setStatus(Message::STATUS_DELIVRED);
        $message->setCreatedAt(new DateTime('2015-01-01T01:00:00', new DateTimeZone('UTC')));
        $manager->persist($message);

        $manager->flush();
    }

    public function getOrder()
    {
        return 2;
    }
}
