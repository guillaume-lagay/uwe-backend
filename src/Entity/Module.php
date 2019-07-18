<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as Serializer;

/**
 * @ORM\Entity
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
     * @ORM\OneToMany(targetEntity="Component", mappedBy="module", cascade="remove")
     * @ORM\JoinColumn(nullable=true)
     * @Assert\Valid()
     * @Serializer\Groups({"module_detail"})
     * */
    private $components;

    /**
     * @ORM\ManyToMany(targetEntity="Student", inversedBy="modules")
     * @ORM\JoinColumn(name="module_students")
     * @Assert\Valid()
     * @Serializer\Groups({"module_detail"})
     * */
    private $students;

    public function __construct()
    {
        $this->components = new ArrayCollection();
        $this->students = new ArrayCollection();
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
    public function getName()
    {
        return $this->name;
    }


    /**
     * @param mixed $components
     * @return Module
     */
    public function setComponents($components)
    {
        $this->components = $components;
        return $this;
    }

    /**
     * @param mixed $students
     * @return Module
     */
    public function setStudents($students)
    {
        $this->students = $students;
        return $this;
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
    public function getAcronym()
    {
        return $this->acronym;
    }

    /**
     * @param mixed $acronym
     */
    public function setAcronym($acronym)
    {
        $this->acronym = $acronym;
        return $this;
    }

    public function addComponent(Component $component) {
        if ($this->components->contains($component)) { return 0; }

        $this->components[] = $component;
        return 1;
    }

    public function removeComponent(Component $component) {
        return $this->components->removeElement($component);
    }

    /**
     * @return mixed
     */
    public function getComponents()
    {
        return $this->components;
    }

    public function addStudent(Student $student) {
        if ($this->students->contains($student)) { return 0; }

        $this->students[] = $student;
        return 1;
    }

    public function removeStudent(Student $student) {
        return $this->students->removeElement($student);
    }

    /**
     * @return mixed
     */
    public function getStudents()
    {
        return $this->students;
    }

    public function getMean()
    {
        $total = 0;
        $coefficients = 0;
        foreach ($this->components as $c) {
            $total += $c->getMean() * $c->getCoefficient();
            $coefficients += $c->getCoefficient();
        }

        if ($coefficients == 0) {
            return 0;
        }

        $mean = $total / $coefficients;

        return $mean;
    }

}