<?php

namespace Charles\MessageBundle\Controller;

use Symfony\Component\HttpFoundation\Request;

use Charles\ApiBundle\Controller\Controller,
    Charles\ApiBundle\Exception\FormNotValidException,
    Charles\MessageBundle\Entity\Message,
    Charles\UserBundle\Exception\UserNotFoundException;

class MessageController extends Controller
{
    public function postMessagesAction(Request $request)
    {
        try {
            $message = $this->get('charles.message')->create($request->request->all(), $this->getUser());
        } catch(FormNotValidException $e) {
            return $this->view($e->getForm());
        }

        return $this->view($message, 201);
    }

    public function aggregMessagesAction(Request $request)
    {
        $data = [
            'content' => str_replace('+', ' ', $request->query->get('text')),
            'source' => Message::SOURCE_SMS,
        ];

        try {
            $user = $this->get('charles.user')->findByIdentifier($request->query->get('msisdn'));
        } catch(UserNotFoundException $e) {
            try {
                $user = $this->get('charles.user')->create([
                    'email' => null,
                    'password' => null,
                    'phone' => null,
                    'identifier' => $request->query->get('msisdn'),
                ]);
            } catch(FormNotValidException $e) {
                return $this->view($e->getForm());
            }
        }

        try {
            $message = $this->get('charles.message')->create($data, $user, conversation);
        } catch(FormNotValidException $e) {
            return $this->view($e->getForm());
        }

        return $this->view('', 200);
    }
}
