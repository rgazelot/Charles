<?php

namespace Charles\MessageBundle\Controller;

use Symfony\Component\HttpFoundation\Request,
    Charles\ApiBundle\Exception\FormNotValidException;

use Charles\ApiBundle\Controller\Controller,
    Charles\MessageBundle\Entity\Message,
    Charles\MessageBundle\Exception\MessageNotFoundException,
    Charles\MessageBundle\EventListener\MessageEvents,
    Charles\MessageBundle\EventListener\MessageEvent,
    Charles\UserBundle\EventListener\UserEvents,
    Charles\UserBundle\EventListener\UserEvent,
    Charles\UserBundle\Exception\UserNotFoundException;

class AggregController extends Controller
{
    public function postMessagesTwilioAction(Request $request)
    {
        $data = [
            'content' => $request->request->get('Body'),
            'source' => Message::SOURCE_SMS,
        ];

        $this->get('monolog.logger.twilio')->info('aggreg', $request->request->all());

        try {
            $user = $this->get('charles.user')->findByPhone($request->request->get('From'));
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
            return $this->view('<?xml version="1.0" encoding="UTF-8" ?><Response></Response>', 200, ['Content-Type' => 'application/xml']);
        }

        $this->get('event_dispatcher')->dispatch(MessageEvents::MESSAGE_CREATED, new MessageEvent($message));

        return $this->view('<?xml version="1.0" encoding="UTF-8" ?><Response></Response>', 200, ['Content-Type' => 'application/xml']);
    }

    public function postMessagesTwilioCallbackAction(Request $request)
    {
        $this->get('monolog.logger.twilio')->info('callback', $request->request->all());

        try {
            $message = $this->get('charles.message')->findByProviderId($request->request->get('MessageSid'));
        } catch(MessageNotFoundException $e) {
            $this->get('monolog.logger.twilio')->critical('message_not_found', $request->request->all());
        }

        $message->setProvider($request->request->get('MessageStatus'));

        $em = $this->get('doctrine.orm.entity_manager');
        $em->persist($message);
        $em->flush();
    }
}
