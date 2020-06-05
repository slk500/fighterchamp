<?php


namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="signup_sparring")
 */
class SignupSparring
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    public $id;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Sparring", inversedBy="signups")
     */
    public Sparring $sparring;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="signUpTournaments")
     * @ORM\JoinColumn(nullable=false)
     */
    public User $user;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Discipline")
     */
    public ?Discipline $discipline = null;

    /**
     * @ORM\Column(type="integer")
     */
    public ?int $weight = null;

    /**
     * @ORM\Column(type="decimal", nullable=true)
     */
    public ?int $trainingTime = null;

    public function __construct(User $user, Sparring $sparring)
    {
        $this->user = $user;
        $this->sparring = $sparring;
        $this->createdAt = new \DateTime();
    }
}