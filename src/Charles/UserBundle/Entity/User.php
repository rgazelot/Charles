<?php

namespace Charles\UserBundle\Entity;

use DateTime;

use Doctrine\ORM\Mapping as ORM,
    Doctrine\Common\Collections\ArrayCollection;

use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert,
    Symfony\Component\Security\Core\User\UserInterface,
    Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(indexes={
 *   @ORM\Index(name="email_idx", columns={"email"}),
 *   @ORM\Index(name="phone_idx", columns={"phone"})
 * })
 * @ORM\Entity(repositoryClass="Charles\UserBundle\Entity\UserRepository")
 */
class User implements UserInterface
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
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
     */
    private $email;

    /**
     * @var string $phone
     *
     * @ORM\Column(name="phone", unique=true, type="string", nullable=true)
     */
    private $phone;

    /**
     * @var string $token
     *
     * @ORM\Column(name="token", unique=true, type="string")
     *
     * @Assert\NotNull()
     * @Assert\NotBlank()
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
     * @ORM\OneToMany(targetEntity="Charles\MessageBundle\Entity\Message", mappedBy="author")
     */
    private $messages;

    public function __construct()
    {
        $this->token = sha1(uniqid(true) . time());
        $this->roles = ['ROLE_USER'];
        $this->messages = new ArrayCollection();
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
