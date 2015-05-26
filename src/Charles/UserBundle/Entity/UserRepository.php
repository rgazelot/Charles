<?php

namespace Charles\UserBundle\Entity;

use Doctrine\ORM\EntityRepository;

class UserRepository extends EntityRepository
{
    public function findByIdentifier($identifier = null) {
        return $this->createQueryBuilder('u')
            ->where('u.identifier = :identifier')
                ->setParameter('identifier', $identifier)
            ->getQuery()
            ->getSingleResult();
    }
}
