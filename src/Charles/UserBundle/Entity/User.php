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
     * @var string $firstname
     *
     * @ORM\Column(name="firstname", type="string", nullable=true)
     *
     * @Expose
     */
    private $firstname;

    /**
     * @var string $firstname
     *
     * @ORM\Column(name="lastname", type="string", nullable=true)
     *
     * @Expose
     */
    private $lastname;

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
     * @var string $gender
     *
     * @ORM\Column(name="gender", type="string", nullable=true)
     *
     * @Assert\Choice(choices = {"male", "female"}, message = "Choose a valid gender.")
     *
     * @Expose
     */
    private $gender;

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
     * @var DateTime $createdAt
     *
     * @ORM\Column(name="createdAt", type="datetime")
     *
     * @Expose
     */
    private $createdAt;

    /**
     * @var string $address
     *
     * @ORM\Column(name="address", type="string", nullable=true)
     *
     * @Expose
     */
    private $address;

    /**
     * @var string $zip
     *
     * @ORM\Column(name="zip", type="string", nullable=true)
     *
     * @Expose
     */
    private $zip;

    /**
     * @var string $city
     *
     * @ORM\Column(name="city", type="string", nullable=true)
     *
     * @Expose
     */
    private $city;

    /**
     * @var string $country
     *
     * @ORM\Column(name="country", type="string", nullable=true)
     *
     * @Expose
     */
    private $country;

    /**
     * @var DateTime $lastMessage
     *
     * @ORM\Column(name="lastMessage", type="datetime", nullable=true)
     *
     * @Expose
     */
    private $lastMessage;

    /**
     * @var DateTime $lastMessagesViewed
     *
     * @ORM\Column(name="lastMessagesViewed", type="datetime", nullable=true)
     *
     * @Expose
     */
    private $lastMessagesViewed;

    /**
     * @var string $budget
     *
     * @ORM\Column(name="budget", type="float", nullable=true)
     *
     * @Expose
     */
    private $budget;

    /**
     * @var string $informations
     *
     * @ORM\Column(name="informations", type="text", nullable=true)
     *
     * @Expose
     */
    private $informations;

    /**
     * @var string $via
     *
     * @ORM\Column(name="via", type="string")
     *
     * @Assert\Choice(choices = {"web", "sms"}, message = "Choose a valid via.")
     */
    private $via;

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

    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getFirstname()
    {
        return $this->firstname;
    }

    public function setLastname($lastname)
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getLastname()
    {
        return $this->lastname;
    }

    public function getName()
    {
        return $this->firstname . ' ' . $this->lastname;
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

    public function setVia($via)
    {
        $this->via = $via;

        return $this;
    }

    public function getVia()
    {
        return $this->via;
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

    public function setGender($gender)
    {
        $this->gender = $gender;

        return $this;
    }

    public function getGender()
    {
        return $this->gender;
    }

    public function setBudget($budget)
    {
        $this->budget = $budget;

        return $budget;
    }

    public function getBudget()
    {
        return $this->budget;
    }

    public function getPhone()
    {
        return $this->phone;
    }

    public function getMessages()
    {
        return $this->messages;
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

    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    public function getAddress()
    {
        return $this->address;
    }

    public function setZip($zip)
    {
        $this->zip = $zip;

        return $zip;
    }

    public function getZip()
    {
        return $this->zip;
    }

    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    public function getCity()
    {
        return $this->city;
    }

    public function setCountry($country)
    {
        $this->country = $country;

        return $this;
    }

    public function getCountry()
    {
        return $this->country;
    }

    public function setLastMessage(DateTime $lastMessage)
    {
        $this->lastMessage = $lastMessage;

        return $this;
    }

    public function getLastMessage()
    {
        return $this->lastMessage;
    }

    public function setLastMessagesViewed(DateTime $lastMessagesViewed)
    {
        $this->lastMessagesViewed = $lastMessagesViewed;

        return $this;
    }

    public function getLastMessagesViewed()
    {
        return $this->lastMessagesViewed;
    }

    public function setInformations($informations)
    {
        $this->informations = $informations;

        return $this;
    }

    public function getInformations()
    {
        return $this->informations;
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
