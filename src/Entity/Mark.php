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
     * @Serializer\Groups({"summary", "details"})
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Student", inversedBy="marks")
     * @ORM\JoinColumn(nullable=true)
     * @Assert\Valid()
     * */
    private $student;

    /**
     * @ORM\ManyToOne(targetEntity="Component", inversedBy="marks")
     * @ORM\JoinColumn(nullable=true)
     * @Assert\Valid()
     * */
    private $component;

    /**
     * @ORM\Column(type="float")
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
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param mixed $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }



}