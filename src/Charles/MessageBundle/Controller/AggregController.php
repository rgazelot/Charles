<?php

namespace Charles\MessageBundle\Controller;

use Symfony\Component\HttpFoundation\Request,
    Charles\ApiBundle\Exception\FormNotValidException;

use Charles\ApiBundle\Controller\Controller,
    Charles\MessageBundle\Entity\Message,
    Charles\MessageBundle\EventListener\MessageEvents,
    Charles\MessageBundle\EventListener\MessageEvent,
    Charles\UserBundle\EventListener\UserEvents,
    Charles\UserBundle\EventListener\UserEvent,
    Charles\UserBundle\Exception\UserNotFoundException;

class AggregController extends Controller
{
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
                    'phone' => $request->request->get('From'),
                    'via' => 'sms'
                ]);

                $this->get('event_dispatcher')->dispatch(UserEvents::USER_CREATED, new UserEvent($user));
            } catch(FormNotValidException $e) {
                return $this->view($e->getForm());
            }
        }

        try {
            $message = $this->get('charles.message')->create($data, $user, null, 'sms');
        } catch(FormNotValidException $e) {
            return $this->view('', 200);
        }

        $this->get('event_dispatcher')->dispatch(MessageEvents::MESSAGE_CREATED, new MessageEvent($message));

        return $this->view('', 200);
    }
}
