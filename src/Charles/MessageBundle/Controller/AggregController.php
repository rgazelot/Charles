<?php

namespace Charles\MessageBundle\Controller;

use Symfony\Component\HttpFoundation\Request,
    Charles\ApiBundle\Exception\FormNotValidException;

use Charles\ApiBundle\Controller\Controller,
    Charles\MessageBundle\Entity\Message,
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
                    'phone' => null,
                    'identifier' => $request->query->get('msisdn'),
                ]);
            } catch(FormNotValidException $e) {
                return $this->view($e->getForm());
            }
        }

        try {
            $message = $this->get('charles.message')->create($data, $user);
        } catch(FormNotValidException $e) {
            return $this->view('', 400);
        }

        return $this->view('', 200);
    }
}
