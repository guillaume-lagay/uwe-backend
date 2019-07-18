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
     * @Serializer\Groups({"module"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=60)
     * @Assert\NotBlank()
     * @Serializer\Groups({"module"})
     *
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=10)
     * @Assert\NotBlank()
     * @Serializer\Groups({"module"})
     */
    private $acronym;

    /**
     * @ORM\OneToMany(targetEntity="Component", mappedBy="module")
     * @ORM\JoinColumn(nullable=true)
     * @Assert\Valid()
     * @Serializer\Groups({"module_detail"})
     * */
    private $components;

    /**
     * @ORM\ManyToMany(targetEntity="Student", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     * @Assert\Valid()
     * @Serializer\Groups({"module_detail"})
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