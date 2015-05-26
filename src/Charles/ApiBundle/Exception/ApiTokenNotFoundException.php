<?php

namespace Charles\ApiBundle\Exception;

use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class ApiTokenNotFoundException extends BadRequestHttpException
{
    public function __construct()
    {
        parent::__construct("An User ApiKey must be passed in query args.");
    }
}
