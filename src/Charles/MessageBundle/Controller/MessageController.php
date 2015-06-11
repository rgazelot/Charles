<?php

namespace Charles\MessageBundle\Controller;

use Symfony\Component\HttpFoundation\Request;

use Charles\ApiBundle\Controller\Controller,
    Charles\ApiBundle\Exception\FormNotValidException,
    Charles\MessageBundle\EventListener\MessageEvents,
    Charles\MessageBundle\EventListener\MessageEvent;

class MessageController extends Controller
{
    public function getUserMessagesAction($id)
    {
        $user = $this->get('charles.user')->get($id);
        $messages = $this->get('charles.message')->findByUser($user);

        return $this->view($messages, 200);
    }

    public function postUserMessagesAction(Request $request, $id)
    {
        $user = $this->get('charles.user')->get($id);

        try {
            $message = $this->get('charles.message')->create($request->request->all(), $this->getUser(), $user);
        } catch(FormNotValidException $e) {
            return $this->view($e->getForm());
        }

        $this->get('event_dispatcher')->dispatch(MessageEvents::MESSAGE_CREATED, new MessageEvent($message));

        return $this->view($message, 201);
    }
}
