<?php

namespace Charles\MessageBundle\Service;

use Doctrine\ORM\EntityManager;

use Doctrine\ORM\NoResultException;

use Symfony\Component\Form\FormFactory;

use Charles\ApiBundle\Exception\FormNotValidException,
    Charles\MessageBundle\Form\MessageType,
    Charles\MessageBundle\Exception\MessageNotFoundException,
    Charles\MessageBundle\Entity\Message as MessageEntity,
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

    /**
     * @param $providerId
     *
     * @return \Charles\MessageBundle\Entity\Message
     */
    public function findByProviderId($providerId)
    {
        try {
            return $this->em->getRepository('CharlesMessageBundle:Message')->findByProviderId($providerId);
        } catch(NoResultException $e) {
            throw new MessageNotFoundException;
        }
    }

    public function findByUser(User $user)
    {
        return $this->em->getRepository('CharlesMessageBundle:Message')->findByUser($user);
    }

    public function create(array $data, User $author, User $replyTo = null, $source = 'app')
    {
        $message = new MessageEntity;
        $message->setAuthor($author);
        $message->setReplyTo($replyTo);
        $message->setSource($source);
        $message->setStatus(MessageEntity::STATUS_QUEUED);

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
