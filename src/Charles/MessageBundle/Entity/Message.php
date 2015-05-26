<?php

namespace Charles\MessageBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert,
    Symfony\Component\Validator\Constraints as Assert;

use Charles\UserBundle\Entity\User;

/**
 * @ORM\Entity(repositoryClass="Charles\MessageBundle\Entity\MessageRepository")
 */
class Message
{
    const SOURCE_SMS = 'sms';
    const SOURCE_APP = 'app';

    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string $content
     *
     * @ORM\Column(name="content", type="string", nullable=true)
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
     */
    private $source;

    /**
     * @var \Charles\UserBundle\Entity\User $author
     *
     * @ORM\ManyToOne(targetEntity="Charles\UserBundle\Entity\User", inversedBy="messages")
     * @ORM\JoinColumn(name="author_id", referencedColumnName="id", nullable=true)
     */
    private $author;

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

    public function setAuthor(User $author = null)
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
        return $this;
    }
}
