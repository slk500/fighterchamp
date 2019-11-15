<?php
declare(strict_types=1);

namespace AppBundle\Entity;

use AppBundle\Repository\TournamentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TournamentRepository")
 * @ORM\Table(name="tournament")
 */
class Tournament
{
    public function __construct()
    {
        $this->userAdmin = new ArrayCollection();
        $this->signUpTournament = new ArrayCollection();
        $this->schedule = new ArrayCollection();
        $this->info = new ArrayCollection();
        $this->fights = new ArrayCollection();
        $this->awards = new ArrayCollection();
    }

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Award", mappedBy="tournament")
     * @ORM\OrderBy({"name" = "ASC"})
     */
    private $awards;

    /**
     * @ORM\Column(type="string")
     * @var string
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Place")
     */
    private $place;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\SignUpTournament", mappedBy="tournament")
     */
    private $signUpTournament;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Schedule", mappedBy="tournament")
     * @ORM\OrderBy({"start" = "ASC"})
     */
    private $schedule;

    /**
     * @ORM\Column(type="datetime")
     */
    private $start;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $end;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $signUpTill;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $signUpStart;

    /**
     * @var $logo string
     * @ORM\Column(type="string", nullable=true)
     */
    private $logo;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $poster;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Info", mappedBy="tournament")
     */
    private $info;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Ticket", mappedBy="tournament")
     * @var ArrayCollection/Ticket[]
     */
    private $tickets;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Fight", mappedBy="tournament")
     * @var ArrayCollection/Fight[]
     */
    private $fights;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @var string
     */
    private $facebookEvent;

    /**
     * @var int
     * @ORM\Column(type="integer")
     */
    private $capacity = 0;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $discipline;

    public function isAvailableSeats(): bool
    {
        return $this->getSignUpTournament()->count() < $this->capacity;
    }

    public function getPaymentInfo(): string
    {
        return
            (($this->getInfo()->filter(function (Info $info) {
                return $info->getType() === 'singUpPayment';
            }))->first())->getDescription();
    }

    public function getFacebookEvent(): ?string
    {
        return $this->facebookEvent;
    }

    public function setFacebookEvent(string $facebookEvent)
    {
        $this->facebookEvent = $facebookEvent;
    }

    public function getStart()
    {
        return $this->start;
    }


    public function setStart($start)
    {
        $this->start = $start;
    }

    public function getEnd()
    {
        return $this->end;
    }

    public function setEnd($end)
    {
        $this->end = $end;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }


    public function getId()
    {
        return $this->id;
    }


    /**
     * @ORM\OneToMany(
     *     targetEntity="UserAdminTournament",
     *     cascade={"persist"},
     *     mappedBy="tournament")
     */
    private $userAdmin;


    public function getUserAdmin()
    {
        return $this->userAdmin;
    }

    ///coś tu jest pokręcone trzeba to zmienić
    public function addUserAdmin(UserAdminTournament $userTournamentAdmin)
    {
        if ($this->userAdmin->contains($userTournamentAdmin)) {
            return;
        }

        $this->userAdmin[] = $userTournamentAdmin;
        // needed to update the owning side of the relationship!
        $userTournamentAdmin->setUser($this);
    }


    public function __toString()
    {
        return (string)$this->getName();
    }


    public function getSignUpTournament()
    {
        return $this->signUpTournament->matching(TournamentRepository::createSignsUpTournamentNotDeleted());
    }


    public function getSchedule()
    {
        return $this->schedule;
    }

    public function getSignUpTill()
    {
        return $this->signUpTill;
    }

    public function setSignUpTill($signUpTill)
    {
        $this->signUpTill = $signUpTill;
    }

    public function getLogo(): ?string
    {
        return $this->logo;
    }

    public function setLogo(string $logo)
    {
        $this->logo = $logo;
    }

    public function getPoster(): ?string
    {
        return $this->poster;
    }

    public function setPoster(string $poster)
    {
        $this->poster = $poster;
    }

    public function getInfo()
    {
        return $this->info;
    }

    public function setInfo(Info $info)
    {
        $this->info = $info;
    }

    /**
     * @return ArrayCollection|Ticket[]
     */
    public function getTickets()
    {
        return $this->tickets;
    }

    public function setTickets($tickets)
    {
        $this->tickets = $tickets;
    }

    public function getPlace()
    {
        return $this->place;
    }

    public function setPlace(Place $place): void
    {
        $this->place = $place;
    }

    public function getCapacity(): int
    {
        return $this->capacity;
    }

    public function setCapacity(int $capacity): void
    {
        $this->capacity = $capacity;
    }

    public function getTicketFighterAdult(): ?Ticket
    {
        $criteria = Criteria::create();
        $criteria->where(Criteria::expr()->eq('isAdult', true));
        $criteria->andWhere(Criteria::expr()->eq('userType', 'fighter'));
        $criteria->getFirstResult();

        $result = $this->tickets->matching($criteria);


        if ($result->isEmpty()) {
            return null;
        }

        return $result = $result->first();
    }

    public function getTicketFighterChild(): ?Ticket
    {
        $criteria = Criteria::create();
        $criteria->where(Criteria::expr()->eq('isAdult', false));
        $criteria->andWhere(Criteria::expr()->eq('userType', 'fighter'));
        $criteria->getFirstResult();

        $result = $this->tickets->matching($criteria);

        if ($result->isEmpty()) {
            return null;
        }

        return $result = $result->first();
    }

    public function getFights(): Collection
    {
        return $this->fights;
    }

    public function getFightsReady(): Collection
    {
        return $this->fights->matching(
            TournamentRepository::createFightsReadyCriteria()
        );
    }

    public function getAwards(): Collection
    {
        return $this->awards;
    }


    public function getSignUpStart()
    {
        return $this->signUpStart;
    }


    public function setSignUpStart($signUpStart): void
    {
        $this->signUpStart = $signUpStart;
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
