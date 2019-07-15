<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as Serializer;

/**
 * @ORM\Entity
 * @ORM\Table
 */
class Student
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Serializer\Groups({"summary", "details"})
     */
    protected $id;

    /**
     * @ORM\OneToOne(targetEntity="User")
     * @ORM\JoinColumn(nullable=true)
     * @Assert\Valid()
     * */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity="Mark", mappedBy="student")
     * @ORM\JoinColumn(nullable=true)
     * @Assert\Valid()
     * */
    private $marks;

    /**
     * @ORM\ManyToMany(targetEntity="Module", cascade={"persist"}, fetch="EAGER")
     * @ORM\JoinColumn(nullable=true)
     * @Assert\Valid()
     * */
    private $modules;
}