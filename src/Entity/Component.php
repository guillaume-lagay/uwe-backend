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
     * @Serializer\Groups({"component"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=60)
     * @Assert\NotBlank()
     * @Serializer\Groups({"component"})
     */
    private $name;

    /**
     * @ORM\Column(type="decimal")
     * @Serializer\Groups({"component"})
     */
    private $coefficient;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\DateTime()
     * @Serializer\Groups({"component"})
     * @Assert\GreaterThan(
     *     "+30 minutes",
     *     message = "Merci de renseigner une date valable (au moins 30 minutes après la date actuelle)")
     */

    private $passDate;
    /**
     * @ORM\ManyToOne(targetEntity="Module", inversedBy="components")
     * @ORM\JoinColumn(nullable=true)
     * @Assert\Valid()
     * @Serializer\Groups({"component_module", "component_detail"})
     * */
    private $module;


    /**
     * @ORM\OneToMany(targetEntity="Mark", mappedBy="component", cascade="remove")
     * @ORM\JoinColumn(nullable=true)
     * @Serializer\Groups({"component_detail", "component_mark"})
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
     * @param mixed $module
     * @return Component
     */
    public function setModule($module)
    {
        $this->module = $module;
        return $this;
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
        foreach ($this->marks as $m) {
            $total += $m->getValue();
        }

        if ($this->marks->count() == 0) {
            return 0;
        }

        $mean = $total / $this->marks->count();

        return $mean;
    }

}