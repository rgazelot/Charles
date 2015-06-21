<?php

namespace Charles\ApiBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController,
    JMS\Serializer\SerializationContext;

class Controller extends FOSRestController
{
    /**
     * {@inheriteDoc}
     */
    public function view($data = null, $statusCode = null, array $headers = [], array $serializers = [])
    {
        $view = parent::view($data, $statusCode, $headers);

        if (!empty($serializers)) {
            $view->setSerializationContext(SerializationContext::create()->setGroups($serializers));
        }

        return $view;
    }
}
