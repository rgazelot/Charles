<?php

namespace Charles\MessageBundle\Entity;

use DateTime;

use Doctrine\ORM\Mapping as ORM;

use JMS\Serializer\Annotation\ExclusionPolicy,
    JMS\Serializer\Annotation\Expose,
    JMS\Serializer\Annotation\Groups,
    JMS\Serializer\Annotation\Type;

use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert,
    Symfony\Component\Validator\Constraints as Assert;

use Charles\UserBundle\Entity\User;

/**
 * @ORM\Entity(repositoryClass="Charles\MessageBundle\Entity\MessageRepository")
 *
 * @ExclusionPolicy("all")
 */
class Message
{
    const SOURCE_SMS = 'sms';
    const SOURCE_APP = 'app';

    const STATUS_QUEUED = 'queued';
    const STATUS_SENDING = 'sending';
    const STATUS_RECEIVING = 'receiving';
    const STATUS_FAILED = 'failed';
    const STATUS_SENT = 'sent';
    const STATUS_DELIVRED = 'delivered';
    const STATUS_UNDELIVERED = 'undelivered';

    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * @Expose
     */
    private $id;

    /**
     * @var string $content
     *
     * @ORM\Column(name="content", type="text")
     *
     * @Assert\NotNull()
     * @Assert\NotBlank()
     *
     * @Expose
     */
    private $content;

    /**
     * @var string $source
     *
     * @ORM\Column(name="source", type="string")
     *
     * @Assert\NotNull()
     * @Assert\NotBlank()
     * @Assert\Choice(choices = {"sms", "app"}, message = "Choose a valid source.")
     *
     * @Expose
     */
    private $source;

    /**
     * @var string $status
     *
     * @ORM\Column(name="status", type="string")
     *
     * @Assert\NotNull()
     * @Assert\NotBlank()
     *
     * @Expose
     */
    private $status;

    /**
     * @var string $providerId
     *
     * @ORM\Column(name="providerId", type="string", nullable=true)
     */
    private $providerId;

    /**
     * @var DateTime $createdAt
     *
     * @ORM\Column(name="createdAt", type="datetime")
     *
     * @Expose
     */
    private $createdAt;

    /**
     * @var \Charles\UserBundle\Entity\User $author
     *
     * @ORM\ManyToOne(targetEntity="Charles\UserBundle\Entity\User", inversedBy="messages")
     * @ORM\JoinColumn(name="authorId", referencedColumnName="id")
     *
     * @Expose
     */
    private $author;

    /**
     * @var \Charles\UserBundle\Entity\User $replyTo
     *
     * @ORM\ManyToOne(targetEntity="Charles\UserBundle\Entity\User", inversedBy="messagesReplyTo")
     * @ORM\JoinColumn(name="replyTo", referencedColumnName="id", nullable=true)
     *
     * @Expose
     */
    private $replyTo;

    public function __construct()
    {
        $this->createdAt = new DateTime;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function setAuthor(User $author)
    {
        $this->author = $author;

        return $this;
    }

    public function getAuthor()
    {
        return $this->author;
    }

    public function setSource($source)
    {
        $this->source = $source;

        return $this;
    }

    public function getSource()
    {
        return $this->source;
    }

    public function setReplyTo(User $replyTo = null)
    {
        $this->replyTo = $replyTo;

        return $this;
    }

    public function getReplyTo()
    {
        return $this->replyTo;
    }

    public function setCreatedAt(DateTime $createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setProviderId($providerId)
    {
        $this->providerId = $providerId;

        return $this;
    }

    public function getProviderId()
    {
        return $this->providerId;
    }
}
