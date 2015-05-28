<?php

namespace Charles\UserBundle\Entity;

use DateTime;

use Doctrine\ORM\Mapping as ORM,
    Doctrine\Common\Collections\ArrayCollection;

use JMS\Serializer\Annotation\ExclusionPolicy,
    JMS\Serializer\Annotation\Expose,
    JMS\Serializer\Annotation\Groups,
    JMS\Serializer\Annotation\Type;

use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert,
    Symfony\Component\Security\Core\User\UserInterface,
    Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(indexes={
 * })
 * @ORM\Entity(repositoryClass="Charles\UserBundle\Entity\UserRepository")
 *
 * @ExclusionPolicy("all")
 */
class User implements UserInterface
{
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
     * @var string $password
     *
     * @ORM\Column(name="password", type="string", length=65, nullable=true)
     */
    private $password;

    /**
     * @var string $email
     *
     * @ORM\Column(name="email", unique=true, type="string", length=150, nullable=true)
     *
     * @Assert\Email()
     *
     * @Expose
     */
    private $email;

    /**
     * @var string $phone
     *
     * @ORM\Column(name="phone", type="string", nullable=true)
     *
     * @Expose
     */
    private $phone;

    /**
     * @var string $token
     *
     * @ORM\Column(name="token", unique=true, type="string")
     *
     * @Assert\NotNull()
     * @Assert\NotBlank()
     *
     * @Expose
     */
    private $token;

    /**
     * @ORM\Column(type="array")
     */
    private $roles;

    /**
     * @var string $identifier
     *
     * @ORM\Column(name="identifier", unique=true, type="string", nullable=true)
     */
    private $identifier;

    /**
     * @var DateTime $createdAt
     *
     * @ORM\Column(name="createdAt", type="datetime")
     *
     * @Expose
     */
    private $createdAt;

    /**
     * @ORM\OneToMany(targetEntity="Charles\MessageBundle\Entity\Message", mappedBy="author")
     */
    private $messages;

    /**
     * @ORM\OneToMany(targetEntity="Charles\MessageBundle\Entity\Message", mappedBy="replyTo")
     */
    private $messagesReplyTo;

    public function __construct()
    {
        $this->token = sha1(uniqid(true) . time());
        $this->roles = ['ROLE_USER'];
        $this->createdAt = new DateTime;
        $this->messages = new ArrayCollection();
        $this->messagesReplyTo = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    public function getPhone()
    {
        return $this->phone;
    }

    public function getMessages()
    {
        return $this->messages;
    }

    public function setIdentifier($identifier)
    {
        $this->identifier = $identifier;

        return $this;
    }

    public function getIdentifier()
    {
        return $this->identifier;
    }

    public function getRoles()
    {
        return $this->roles;
    }

    public function setToken($token)
    {
        $this->token = $token;

        return $token;
    }

    public function getToken()
    {
        return $this->token;
    }

    /**
     * {@inheriteDoc}
     */
    public function getSalt()
    {
        return null;
    }

    /**
     * {@inheriteDoc}
     */
    public function getUsername()
    {
        return $this->email;
    }

    /**
     * {@inheriteDoc}
     */
    public function eraseCredentials()
    {
    }
}
