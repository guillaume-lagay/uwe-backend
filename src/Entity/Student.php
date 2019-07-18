<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as Serializer;

/**
 * @ORM\Entity
 * @ORM\Table
 */
class Student extends User
{
    /**
     * @ORM\OneToMany(targetEntity="Mark", mappedBy="student")
     * @ORM\JoinColumn(nullable=true)
     * @Assert\Valid()
     * @Serializer\Groups({"student_detail"})
     * */
    private $marks;

    /**
     * @ORM\ManyToMany(targetEntity="Module", mappedBy="students")
     * @Assert\Valid()
     * @Serializer\Groups({"student_detail"})
     * */
    private $modules;

    /**
     * @return mixed
     */
    public function getMarks()
    {
        return $this->marks;
    }

    /**
     * @param mixed $marks
     * @return Student
     */
    public function setMarks($marks)
    {
        $this->marks = $marks;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getModules()
    {
        return $this->modules;
    }

    /**
     * @param mixed $modules
     * @return Student
     */
    public function setModules($modules)
    {
        $this->modules = $modules;
        return $this;
    }


}