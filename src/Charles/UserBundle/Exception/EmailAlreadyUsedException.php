<?php

namespace Charles\UserBundle\Exception;

use Symfony\Component\HttpKernel\Exception\ConflictHttpException;

class EmailAlreadyUsedException extends ConflictHttpException
{}
