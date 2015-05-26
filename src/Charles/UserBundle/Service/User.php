<?php

namespace Charles\UserBundle\Service;

use Symfony\Component\Form\FormFactory;

use Doctrine\ORM\EntityManager;

use Charles\ApiBundle\Exception\FormNotValidException,
    Charles\UserBundle\Entity\User as UserEntity,
    Charles\UserBundle\Exception\UserNotFoundException,
    Charles\UserBundle\Form\UserType;

class User
{
    private $formFactory;
    private $em;

    public function __construct(FormFactory $formFactory, EntityManager $em)
    {
        $this->formFactory = $formFactory;
        $this->em = $em;
    }

    public function get($id)
    {
        $user = $this->em->getRepository('CharlesUserBundle:User')->find($id);

        if (null === $user) {
            throw new UserNotFoundException($id);
        }

        return $user;
    }

    public function create(array $data = [])
    {
        $user = new UserEntity;

        $form = $this->formFactory->create(new UserType, $user);
        $form->submit($data);

        if (!$form->isValid()) {
            throw new FormNotValidException($form);
        }

        $this->em->persist($user);
        $this->em->flush();

        return $user;
    }
}
