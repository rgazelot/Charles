<?php

namespace Charles\UserBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture,
    Doctrine\Common\DataFixtures\OrderedFixtureInterface,
    Doctrine\Common\Persistence\ObjectManager;

use Charles\UserBundle\Entity\User;

class LoadUserData extends AbstractFixture
{
    public function load(ObjectManager $manager)
    {
        $user = new User;
        $user->setEmail('remy@charles.com');
        $user->setPassword('foo');
        $user->setPhone('0601010101');

        $manager->persist($user);
        $manager->flush();
    }

    public function getOrder()
    {
        return 1;
    }
}
