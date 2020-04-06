<?php
declare(strict_types = 1);

namespace AppBundle\Entity;

use AppBundle\Entity\Traits\TimestampableTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\OrderBy;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\FightRepository")
 * @ORM\Table(name="fight")
 */
class Fight
{
    use TimestampableTrait;

    public function __construct(string $formula, string $weight)
    {
        $this->formula = $formula;
        $this->weight = $weight;
        $this->usersFight = new ArrayCollection();
    }

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Tournament", inversedBy="fights")
     * @ORM\JoinColumn(nullable=true)
     * @var Tournament
     */
    private $tournament;

    /**
     * @ORM\Column(type="string")
     */
    private $formula;

    /**
     * @ORM\Column(type="string")
     */
    private $weight;

    /**
     * @ORM\Column(type="integer",nullable=true)
     */
    private $position;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isVisible = false;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $day;

    /**
     * @ORM\Column(type="string",nullable=true)
     */
    private $youtubeId;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\UserFight", mappedBy="fight", cascade={"remove"})
     * @OrderBy({"isRedCorner" = "DESC"})
     */
    private $usersFight;

    /**
     * @var string
     * @ORM\Column(type="text")
     */
    private $description ='';

    /**
     * @var bool
     * @ORM\Column(type="boolean", nullable=false)
     */
    private $licence = false;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    private $discipline;

    public function isLicence(): bool
    {
        return $this->licence;
    }

    public function setLicence(bool $licence): void
    {
        $this->licence = $licence;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function addUserFight(UserFight $userFight)
    {
//        if ($this->usersFight->contains($userFight))
//        {
//            return;
//        }

        $userFight->setFight($this);

        return $this;
    }

    public function getUsersFight(): Collection
    {
        return $this->usersFight;
    }


    public function getIsVisible(): bool
    {
        return $this->isVisible;
    }


    public function setIsVisible(bool $isVisible)
    {
        $this->isVisible = $isVisible;
    }

    public function toggleReady()
    {
        $this->isVisible = $this->isVisible ? false : true;
    }


    public function getTournament()
    {
        return $this->tournament;
    }


    public function setTournament($tournament)
    {
        $this->tournament = $tournament;
    }

    public function getFormula()
    {
        return $this->formula;
    }


    public function getWeight()
    {
        return $this->weight;
    }


    public function getPosition(): ?int
    {
        return $this->position;
    }


    public function setPosition(int $position)
    {
        $this->position = $position;
    }

    public function __toString()
    {
        return (string)$this->getFormula();
    }


    public function getDay(): \DateTime
    {
        return $this->day;
    }


    public function setDay(\DateTime $day)
    {
        $this->day = $day;
    }

    public function getYoutubeId(): ?string
    {
        return $this->youtubeId;
    }

    public function setYoutubeId(string $youtubeId)
    {
        $this->youtubeId = $youtubeId;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getDiscipline(): ?string
    {
        return $this->discipline;
    }

    public function setDiscipline(string $discipline): void
    {
        $this->discipline = $discipline;
    }
}
