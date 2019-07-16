<?php

namespace App\Entity;

use Doctrine\ORM\Mapping\DiscriminatorColumn;
use Doctrine\ORM\Mapping\DiscriminatorMap;
use Doctrine\ORM\Mapping\InheritanceType;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as Serializer;

/**
 * @InheritanceType("SINGLE_TABLE")
 * @DiscriminatorColumn(name="user_type", type="string")
 * @DiscriminatorMap({"user" = "User", "student" = "Student"})
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 *
*/
class User extends BaseUser
{


    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Serializer\Groups({"student"})
     */
    protected $id;

    /**
     * @Serializer\Groups({"student"})
     */
    protected $email;

    /**
     * @ORM\Column(type="string", length=40)
     * @Assert\NotBlank()
     * @Serializer\Groups({"student"})
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=40)
     * @Assert\NotBlank()
     * @Serializer\Groups({"student"})
     */
    private $lastName;

    /**
     * @ORM\Column(type="string", length=80)
     * @Assert\NotBlank()
     * @Serializer\Groups({"student"})
     */
    private $address;

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param mixed $address
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @param mixed $firstName
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @param mixed $lastName
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

}