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
     * @ORM\Column(type="decimal")
     */
    private $coefficient;

    /**
     * @ORM\ManyToOne(targetEntity="Module", inversedBy="components")
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

}