<?php
/**
 * Created by PhpStorm.
 * User: Guitou
 * Date: 15/07/2019
 * Time: 12:51
 */

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as Serializer;

/**
 * @ORM\Entity
 * @ORM\Table
 */
class Mark
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Serializer\Groups({"mark","mark_detail"})
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Student", inversedBy="marks")
     * @ORM\JoinColumn(nullable=true)
     * @Assert\Valid()
     * @Serializer\Groups({"mark_detail"})
     * */
    private $student;

    /**
     * @ORM\ManyToOne(targetEntity="Component", inversedBy="marks")
     * @ORM\JoinColumn(nullable=true)
     * @Assert\Valid()
     * @Serializer\Groups({"mark_detail"})
     * */
    private $component;

    /**
     * @ORM\Column(type="float")
     * @Serializer\Groups({"mark","mark_detail"})
     */
    private $value;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return Mark
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getStudent()
    {
        return $this->student;
    }

    /**
     * @param mixed $student
     * @return Mark
     */
    public function setStudent($student)
    {
        $this->student = $student;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getComponent()
    {
        return $this->component;
    }

    /**
     * @param mixed $component
     * @return Mark
     */
    public function setComponent($component)
    {
        $this->component = $component;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param mixed $value
     * @return Mark
     */
    public function setValue($value)
    {
        $this->value = $value;
        return $this;
    }



}