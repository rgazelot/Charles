<?php

namespace Charles\MessageBundle\EventListener;

use Symfony\Component\EventDispatcher\Event;

use Charles\MessageBundle\Entity\Message;

class MessageEvent extends Event
{
    private $message;

    public function __construct(Message $message)
    {
        $this->message = $message;
    }

    public function getMessage()
    {
        return $this->message;
    }
}
