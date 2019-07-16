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
class Component
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
     * @ORM\Column(type="decimal")
     */
    private $coefficient;

    /**
     * @ORM\ManyToMany(targetEntity="Module")
     * @ORM\JoinColumn(nullable=true)
     * @Assert\Valid()
     * */
    private $module;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\DateTime()
     * @Assert\GreaterThan(
     *     "+30 minutes",
     *     message = "Merci de renseigner une date valable (au moins 30 minutes après la date actuelle)")
     */
    private $passDate;

    /**
     * @ORM\OneToMany(targetEntity="Mark", mappedBy="component")
     * @ORM\JoinColumn(nullable=true)
     * @Assert\Valid()
     * */
    private $marks;

    /**
     * Component constructor.
     */
    public function __construct()
    {
        $this->marks = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getCoefficient()
    {
        return $this->coefficient;
    }

    /**
     * @param mixed $coefficient
     */
    public function setCoefficient($coefficient)
    {
        $this->coefficient = $coefficient;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getModule()
    {
        return $this->module;
    }

    /**
     * @return mixed
     */
    public function getPassDate()
    {
        return $this->passDate;
    }

    /**
     * @param mixed $passDate
     */
    public function setPassDate($passDate)
    {
        $this->passDate = $passDate;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMarks()
    {
        return $this->marks;
    }

    public function getMean() {
        $total = 0;
        foreach ($m as $this->marks) {
            $total += $m->getValue();
        }

        $mean = $total / $this->marks->count();

        return $mean;
    }

}