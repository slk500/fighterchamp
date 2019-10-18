<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="user_coach")
 */
class UserCoach
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="userCoachFighters")
     * @ORM\JoinColumn(nullable=false)
     */
    private $coach;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="userCoachCoaches")
     * @ORM\JoinColumn(nullable=false)
     */
    private $fighter;

    public function __construct(User $fighter, User $coach)
    {
        $this->fighter = $fighter;
        $this->coach = $coach;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getCoach(): User
    {
        return $this->coach;
    }

    public function getFighter(): User
    {
        return $this->fighter;
    }
}
