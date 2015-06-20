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
        $user->setEmail('testMessages@charles.com');
        $user->setPassword('pass');
        $user->setToken('testMessagesToken');
        $user->setPhone('0601010101');
        $user->setIdentifier('testMessages');
        $userApi->encodePassword($user);
        $manager->persist($user);
        $this->setReference('user.create_messages', $user);

        $user = new User;
        $user->setEmail('testGetAdminMessages@charles.com');
        $user->setPassword('pass');
        $user->setToken('testGetAdminMessagesToken');
        $user->setPhone('0601010101');
        $user->setIdentifier('testGetAdminMessages');
        $userApi->encodePassword($user);
        $manager->persist($user);
        $this->setReference('user.get_messages.admin', $user);

        $user = new User;
        $user->setEmail('testUserMessages@charles.com');
        $user->setPassword('pass');
        $user->setToken('testUserMessagesToken');
        $user->setPhone('0601010101');
        $user->setIdentifier('testUserMessages');
        $userApi->encodePassword($user);
        $manager->persist($user);
        $this->setReference('user.get_messages.user', $user);

        $user = new User;
        $user->setEmail('testEdit@charles.com');
        $user->setPassword('pass');
        $user->setToken('testEditToken');
        $user->setPhone('0601010101');
        $user->setIdentifier('testEdit');
        $userApi->encodePassword($user);
        $manager->persist($user);
        $this->setReference('user.edit_messages', $user);

        $manager->flush();
    }

    public function getOrder()
    {
        return 1;
    }
}
