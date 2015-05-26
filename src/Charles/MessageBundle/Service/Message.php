<?php

namespace Charles\MessageBundle\Service;

use Doctrine\ORM\EntityManager;

use Symfony\Component\Form\FormFactory;

use Charles\ApiBundle\Exception\FormNotValidException,
    Charles\MessageBundle\Form\MessageType,
    Charles\MessageBundle\Entity\Message as MessageEntity,
    Charles\MessageBundle\Entity\Conversation,
    Charles\UserBundle\Entity\User;

class Message
{
    private $formFactory;
    private $em;

    public function __construct(FormFactory $formFactory, EntityManager $em)
    {
        $this->formFactory = $formFactory;
        $this->em = $em;
    }

    public function create(array $data, User $author, Conversation $conversation = null)
    {
        $message = new MessageEntity;
        $message->setAuthor($author);
        $message->setSource(isset($data['source']) ? $data['source'] : null);
        $message->setConversation($conversation);

        $form = $this->formFactory->create(new MessageType, $message, ['allow_extra_fields' => true]);
        $form->submit($data);

        if (!$form->isValid()) {
            throw new FormNotValidException($form);
        }

        $this->em->persist($message);
        $this->em->flush();

        return $message;
    }
}
