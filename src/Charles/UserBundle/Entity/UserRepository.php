<?php

namespace Charles\UserBundle\Entity;

use Doctrine\ORM\EntityRepository;

class UserRepository extends EntityRepository
{
    public function findByPhone($phone = null) {
        return $this->createQueryBuilder('u')
            ->where('u.phone = :phone')
                ->setParameter('phone', $phone)
            ->getQuery()
            ->getSingleResult();
    }

    public function findByEmail($email) {
        return $this->createQueryBuilder('u')
            ->where('u.email = :email')
            ->setParameter('email', $email)
            ->getQuery()
            ->getSingleResult();
    }

    public function all()
    {
        return $this->createQueryBuilder('u')
            ->getQuery()
            ->getResult();
    }
}
