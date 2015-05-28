<?php

namespace Charles\MessageBundle\Entity;

use Doctrine\ORM\EntityRepository;

use Charles\UserBundle\Entity\User;

class MessageRepository extends EntityRepository
{
    public function findByUser(User $user)
    {
        $qb = $this->createQueryBuilder('m')
            ->addSelect('a')
                ->leftJoin('m.author', 'a')
            ->addSelect('r')
                ->leftJoin('m.replyTo', 'r');

        return $qb->where($qb->expr()->orX(
                $qb->expr()->eq('m.author', ':user'),
                $qb->expr()->eq('m.replyTo', ':user')
            ))
            ->setParameter('user', $user)
            ->orderBy('m.createdAt')
            ->getQuery()
            ->getResult();
    }
}
