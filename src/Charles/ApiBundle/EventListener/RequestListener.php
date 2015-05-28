<?php

namespace Charles\ApiBundle\EventListener;

use Symfony\Component\HttpKernel\Event\GetResponseEvent,
    Symfony\Component\HttpKernel\HttpKernelInterface;

use Charles\ApiBundle\Exception\ApiTokenNotFoundException,
    Charles\ApiBundle\Service\Login;

/**
 * Listener that is responsible to catch all api request to retrieve token from Headers or query
 * and log the user with it.
 */
class RequestListener
{
    private $login;

    public function __construct(Login $login)
    {
        $this->login = $login;
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        $request = $event->getRequest();

        /**
         * Do not take care of the authentication in some requests :
         * - Not a Master request
         * - Not match api_* pattern
         * - Not in wildcard routes
         */
        if ($event->getRequestType() !== HttpKernelInterface::MASTER_REQUEST || !preg_match('/^api/', $request->attributes->get('_route')) || in_array($request->attributes->get('_route'), $this->getWildcardRoutes())) {
            return;
        }

        $token = $request->headers->get('Token', $request->query->get('token'));

        if (null === $token) {
            throw new ApiTokenNotFoundException;
        }

        $this->login->authenticate($token);
    }

    private function getWildcardRoutes()
    {
        return [
            'api_aggreg_messages',
            'api_post_authentications',
            'api_post_users',
        ];
    }
}
