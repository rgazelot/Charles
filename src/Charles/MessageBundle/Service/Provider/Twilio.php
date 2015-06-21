<?php

namespace Charles\MessageBundle\Service\Provider;

use Exception,
    InvalidArgumentException;

use Psr\Log\LoggerInterface,
    Psr\Log\NullLogger;

use Services_Twilio as Base;

use Charles\MessageBundle\Exception\TwilioException;

class Twilio extends Base
{
    private $isActive;
    private $from;
    private $logger;

    public function __construct($sid, $token, $from, LoggerInterface $logger = null)
    {
        parent::__construct($sid, $token);

        $this->isActive = null !== $sid;
        $this->from = $from;
        $this->logger = null !== $logger ? $logger : new NullLogger;
    }

    public function create($to, $body)
    {
        $this->logger->info('send_message', [
            'from' => $this->from,
            'to' => $to,
            'body' => $body,
        ]);

        try {
            $message = $this->account->messages->create(array(
                "From" => $this->from,
                "To" => $to,
                "Body" => $body,
            ));
        } catch(\Services_Twilio_RestException $e) {
            $this->logger->warning('error', [
                'from' => $this->from,
                'to' => $to,
                'body' => $body,
                'error' => $e->getMessage(),
            ]);

            throw new TwilioException;
        }

        return $message;
    }

    public function isActive()
    {
        return $this->isActive;
    }

    public function getFrom()
    {
        return $this->from;
    }
}
