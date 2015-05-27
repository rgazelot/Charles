<?php

namespace Charles\UserBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture,
    Doctrine\Common\DataFixtures\OrderedFixtureInterface,
    Doctrine\Common\Persistence\ObjectManager;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

use Charles\UserBundle\Entity\User;

class LoadUserData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{
    private $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function load(ObjectManager $manager)
    {
        $userApi = $this->container->get('charles.user');

        $user = new User;
        $user->setEmail('test@charles.com');
        $user->setPassword('pass');
        $user->setToken('testToken');
        $user->setPhone('0601010101');
        $user->setIdentifier('testIdentifier');
        $userApi->encodePassword($user);
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
