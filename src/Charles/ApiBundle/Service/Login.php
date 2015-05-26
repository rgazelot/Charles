<?php

namespace Charles\ApiBundle\Service;

use Doctrine\ORM\EntityManager,
    Doctrine\ORM\NoResultException;

use Symfony\Component\Security\Core\SecurityContextInterface,
    Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken,
    Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Login
{
    private $em;
    private $securityContext;

    public function __construct(EntityManager $em, SecurityContextInterface $securityContext)
    {
        $this->em = $em;
        $this->securityContext = $securityContext;
    }

    public function authenticate($token)
    {
        $user = $this->em->getRepository('CharlesUserBundle:User')->findOneByToken($token);

        if (null === $user) {
            throw new NotFoundHttpException('User not found');
        }

        $this->securityContext->setToken(new UsernamePasswordToken($user, null, 'main', $user->getRoles()));
    }
}
