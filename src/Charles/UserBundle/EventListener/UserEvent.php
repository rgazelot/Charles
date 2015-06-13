<?php

namespace Charles\UserBundle\EventListener;

use Symfony\Component\EventDispatcher\Event;

use Charles\UserBundle\Entity\User;

class UserEvent extends Event
{
    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function getUser()
    {
        return $this->user;
    }
}
