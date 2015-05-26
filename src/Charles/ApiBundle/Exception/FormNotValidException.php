<?php

namespace Charles\ApiBundle\Exception;

use InvalidArgumentException;

use Symfony\Component\Form\Form;

class FormNotValidException extends InvalidArgumentException
{
    private $form;

    public function __construct(Form $form)
    {
        $this->form = $form;
    }

    public function getForm()
    {
        return $this->form;
    }
}
