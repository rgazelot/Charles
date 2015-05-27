<?php

namespace Charles\ApiBundle\Controller;

use Symfony\Component\HttpFoundation\Request;

use Charles\UserBundle\Exception\UserNotFoundException;

class ApiController extends Controller
{
    public function postAuthenticationsAction(Request $request)
    {
        if (!$request->request->has('email')) {
            return $this->view(['code' => 'invalid_parameters', 'message' => 'email required'], 400);
        }

        if (!$request->request->has('password')) {
            return $this->view(['code' => 'invalid_parameters', 'message' => 'password required'], 400);
        }

        try {
            $user = $this->get('charles.user')->findByEmail($request->request->get('email'));
        } catch(UserNotFoundException $e) {
            return $this->view(['code' => 'invalid_credentials', 'message' => 'Invalid credentials'], 400);
        }

        $passwordEncoded = $this->get('security.encoder_factory')->getEncoder($user)->encodePassword($request->request->get('password'), $user->getSalt());

        if ($user->getPassword() !== $passwordEncoded) {
            return $this->view(['code' => 'invalid_credentials', 'message' => 'Invalid credentials'], 400);
        }

        return $this->view($user, 200);
    }
}
