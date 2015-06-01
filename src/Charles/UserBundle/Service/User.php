<?php

namespace Charles\UserBundle\Service;

use Symfony\Component\Form\FormFactory,
    Symfony\Component\Security\Core\Encoder\EncoderFactory;

use Doctrine\ORM\EntityManager,
    Doctrine\ORM\NoResultException;

use Charles\ApiBundle\Exception\FormNotValidException,
    Charles\UserBundle\Entity\User as UserEntity,
    Charles\UserBundle\Exception\UserNotFoundException,
    Charles\UserBundle\Exception\EmailAlreadyUsedException,
    Charles\UserBundle\Form\UserType;

class User
{
    private $formFactory;
    private $em;
    private $encoder;

    public function __construct(FormFactory $formFactory, EntityManager $em, EncoderFactory $encoder)
    {
        $this->formFactory = $formFactory;
        $this->em = $em;
        $this->encoder = $encoder;
    }

    public function get($id)
    {
        $user = $this->em->getRepository('CharlesUserBundle:User')->find($id);

        if (null === $user) {
            throw new UserNotFoundException($id);
        }

        return $user;
    }

    public function all()
    {
        return $this->em->getRepository('CharlesUserBundle:User')->all();
    }

    public function findByIdentifier($identifier = null)
    {
        try {
            return $this->em->getRepository('CharlesUserBundle:User')->findByIdentifier($identifier);
        } catch(NoResultException $e) {
            throw new UserNotFoundException;
        }
    }

    public function findByEmail($email)
    {
        try {
            return $this->em->getRepository('CharlesUserBundle:User')->findByEmail($email);
        } catch(NoResultException $e) {
            throw new UserNotFoundException;
        }
    }

    public function create(array $data = [])
    {
        $user = new UserEntity;

        $form = $this->formFactory->create(new UserType, $user, ['allow_extra_fields' => true]);
        $form->submit($data);

        if (!$form->isValid()) {
            throw new FormNotValidException($form);
        }

        try {
            $this->findByEmail($user->getEmail());

            throw new EmailAlreadyUsedException;
        } catch(UserNotFoundException $e) {

        }

        $this->em->persist($user);
        $this->em->flush();

        return $user;
    }

    public function encodePassword(UserEntity $user)
    {
        $user->setPassword($this->encoder->getEncoder($user)->encodePassword($user->getPassword(), $user->getSalt()));
    }
}
