<?php

namespace Charles\UserBundle\Exception;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UserNotFoundException extends NotFoundHttpException
{
    public function __construct($id)
    {
        parent::__construct(sprintf("User with the id %d was not found", $id));
    }
}
