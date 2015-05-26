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
        $user->setToken('myToken');
        $user->setPhone('0601010101');
        $user->setIdentifier('myIdentifier');
        $manager->persist($user);

        $user = new User;
        $user->setIdentifier('client1Identifier');
        $manager->persist($user);

        $manager->flush();
    }

    public function getOrder()
    {
        return 1;
    }
}
