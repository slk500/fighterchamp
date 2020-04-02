<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Faker\Provider\DateTime;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\SignUpTournamentRepository")
 * @ORM\Table(name="signuptournament")
 */
class SignUpTournament
{
    public function __construct(User $user, Tournament $tournament)
    {
        $this->user = $user;
        $this->tournament = $tournament;
        $this->created_at = new \DateTime();
    }

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Assert\NotBlank()
     * @ORM\Column(type="string")
     */
    private $weight;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $weighted;

    /**
     * @Assert\NotBlank()
     * @ORM\Column(type="string")
     */
    private $formula;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Tournament", inversedBy="signUpTournament")
     * @ORM\JoinColumn(nullable=false)
     */
    private $tournament;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="signUpTournaments")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isPaid = false;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isLicence = false;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $deleted_at;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $deletedAtByAdmin;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     * @Assert\Range(
     *      min = 1,
     *      max = 100
     * )
     */
    private $trainingTime;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $discipline;

    public function getIsLicence()
    {
        return $this->isLicence;
    }

    public function setIsLicence($isLicence): void
    {
        $this->isLicence = $isLicence;
    }

    public function getTrainingTime()
    {
        return $this->trainingTime;
    }


    public function setTrainingTime($trainingTime)
    {
        $this->trainingTime = $trainingTime;
    }


    public function getDeletedAt()
    {
        return $this->deleted_at;
    }


    public function delete()
    {
        $this->deleted_at = new \DateTime();
    }


    public function isPaid()
    {
        return $this->isPaid;
    }

    public function setIsPaid($isPaid)
    {
        $this->isPaid = $isPaid;
    }


    public function getId()
    {
        return $this->id;
    }


    public function getWeight(): ?string
    {
        return $this->weight;
    }


    public function setWeight($weight)
    {
        $this->weight = $weight;
    }

    /**
     * @return mixed
     */
    public function getFormula()
    {
        return $this->formula;
    }

    /**
     * @param mixed $formula
     */
    public function setFormula($formula)
    {
        $this->formula = $formula;
    }


    public function getTournament() : Tournament
    {
        return $this->tournament;
    }

    /**
     * @param mixed $tournament
     */
    public function setTournament($tournament)
    {
        $this->tournament = $tournament;
    }


    public function getUser() : User
    {
        return $this->user;
    }

    public function getStazTreningowy()
    {
        if ($this->getTrainingTime()) {
            return '(staż ' . $this->getTrainingTime() . "miesiące) ";
        }
        return null;
    }

    public function getWeighted(): ?string
    {
        return $this->weighted;
    }

    public function setWeighted(string $weighted)
    {
        $this->weighted = $weighted;
    }

    public function getFinallWeight()
    {
        return $this->weighted ?? $this->weight;
    }

    public function getDeletedAtByAdmin(): ?\DateTime
    {
        return $this->deletedAtByAdmin;
    }

    public function setDeleteByAdmin(?\DateTime $dateTime): void
    {
        $this->deletedAtByAdmin = $dateTime;
    }

    public function getDiscipline(): string
    {
        return $this->discipline;
    }

    public function setDiscipline(string $discipline): void
    {
        $this->discipline = $discipline;
    }
}
