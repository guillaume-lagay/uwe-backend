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
class Module
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Serializer\Groups({"summary", "details"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=60)
     * @Assert\NotBlank()
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=10)
     * @Assert\NotBlank()
     */
    private $acronym;

    /**
     * @ORM\OneToMany(targetEntity="Component", mappedBy="modules")
     * @ORM\JoinColumn(nullable=true)
     * @Assert\Valid()
     * */
    private $components;

    /**
     * @ORM\ManyToMany(targetEntity="Student", cascade={"persist"}, fetch="EAGER")
     * @ORM\JoinColumn(nullable=true)
     * @Assert\Valid()
     * */
    private $students;

    /**
     * Module constructor.
     * @param $id
     */
    public function __construct()
    {
        $this->components = new ArrayCollection();
    }


//    public function getMean()
//    {
//        $mean;
//        foreach ($c as $components) {
//            $mean += $c->getMean();
//        }
//
//        $mean = $mean / $components . length();
//    }

}