<?php

namespace Charles\UserBundle\Entity;

use DateTime;

use Doctrine\ORM\Mapping as ORM;

use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert,
    Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(indexes={
 *   @ORM\Index(name="email_idx", columns={"email"}),
 *   @ORM\Index(name="phone_idx", columns={"phone"})
 * })
 * @ORM\Entity(repositoryClass="Charles\UserBundle\Entity\UserRepository")
 */
class User
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
     *
     * @Assert\NotNull()
     * @Assert\NotBlank()
     */
    private $password;

    /**
     * @var string $email
     *
     * @ORM\Column(name="email", unique=true, type="string", length=150)
     *
     * @Assert\NotNull()
     * @Assert\NotBlank()
     * @Assert\Email()
     */
    private $email;

    /**
     * @var string $phone
     *
     * @ORM\Column(name="phone", unique=true, type="string")
     *
     * @Assert\NotNull()
     * @Assert\NotBlank()
     */
    private $phone;

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
}
