<?php

namespace Charles\UserBundle\Controller;

use Symfony\Component\HttpFoundation\Request;

use Charles\ApiBundle\Controller\Controller,
    Charles\ApiBundle\Exception\FormNotValidException,
    Charles\UserBundle\Exception\UserNotFoundException,
    Charles\UserBundle\EventListener\UserEvents,
    Charles\UserBundle\EventListener\UserEvent;

class UserController extends Controller
{
    public function getUserAction($id)
    {
        try {
            $user = $this->get('charles.user')->get($id);
        } catch(UserNotFoundException $e) {
            return $this->view(['error' => ['code' => 'user_not_found', 'message' => $e->getMessage()]], 404);
        }

        return $this->view($user, 200);
    }

    public function getUsersAction()
    {
        $users = $this->get('charles.user')->all();

        return $this->view($users, 200);
    }

    public function postUsersAction(Request $request)
    {
        try {
            $user = $this->get('charles.user')->create($request->request->all());
        } catch(FormNotValidException $e) {
            return $this->view($e->getForm(), 400);
        }

        $this->get('event_dispatcher')->dispatch(UserEvents::USER_CREATED, new UserEvent($user));

        return $this->view($user, 201);
    }

    public function patchUserAction(Request $request, $id)
    {
        try {
            $user = $this->get('charles.user')->get($id);
        } catch(UserNotFoundException $e) {
            return $this->view(['error' => ['code' => 'user_not_found', 'message' => $e->getMessage()]], 404);
        }

        try {
            $this->get('charles.user')->edit($user, $request->request->all());
        } catch(FormNotValidException $e) {
            return $this->view($e->getForm(), 400);
        }

        return $this->view($user, 200);
    }
}
