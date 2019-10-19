<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="ticket")
 */
class Ticket
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @var integer
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Tournament", inversedBy="tickets")
     * @ORM\JoinColumn(nullable=false)
     * @var Tournament
     */
    private $tournament;

    /**
     * @ORM\Column(type="integer")
     * @var integer
     */
    private $price;

    /**
     * @ORM\Column(type="string")
     * @var string
     */
    private $userType;

    /**
     * @var bool
     * @ORM\Column(type="boolean")
     */
    private $isAdult;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $promoUntil;

    /**
     * @var bool
     * @ORM\Column(type="boolean")
     */
    private $insurance;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id)
    {
        $this->id = $id;
    }

    public function getPrice(): int
    {
        return $this->price;
    }

    public function setPrice(int $price)
    {
        $this->price = $price;
    }

    public function getUserType(): string
    {
        return $this->userType;
    }

    public function setUserType(string $userType)
    {
        $this->userType = $userType;
    }

    public function getTournament(): Tournament
    {
        return $this->tournament;
    }

    public function setTournament(Tournament $tournament)
    {
        $this->tournament = $tournament;
    }

    public function isAdult(): bool
    {
        return $this->isAdult;
    }

    public function setIsAdult(bool $isAdult): void
    {
        $this->isAdult = $isAdult;
    }

    public function getPromoUntil(): ?\DateTime
    {
        return $this->promoUntil;
    }

    public function setPromoUntil(\DateTime $promoUntil): void
    {
        $this->promoUntil = $promoUntil;
    }

    public function isInsurance(): bool
    {
        return $this->insurance;
    }

    public function setInsurance(bool $insurance): void
    {
        $this->insurance = $insurance;
    }
}
