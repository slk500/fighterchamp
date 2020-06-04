<?php

declare(strict_types = 1);

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="sparring")
 */
class Sparring
{
    /**
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="integer")
     */
    private $capacity;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\SignupSparring", mappedBy="sparring")
     */
    private $signups;

    /**
     * @var Club
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Club")
     */
    private $club;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $start;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $end;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $createdAt;

    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     */
    private $disciplines;

    public function __construct()
    {
        $this->createdAt = \DateTime::createFromFormat('U', (string) time());
        $this->signups = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id): void
    {
        $this->id = $id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name): void
    {
        $this->name = $name;
    }

    public function getSignups(): Collection
    {
        return $this->signups;
    }

    public function setSignups(Collection $signups): void
    {
        $this->signups = $signups;
    }

    public function getStart()
    {
        return $this->start;
    }

    public function setStart($start): void
    {
        $this->start = $start;
    }

    public function getEnd()
    {
        return $this->end;
    }

    public function setEnd($end): void
    {
        $this->end = $end;
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function setDisciplines($disciplines)
    {
        $this->disciplines = $disciplines;
    }

    public function getDisciplines(): ?string
    {
        return $this->disciplines;
    }

    public function setClub(Club $club)
    {
        $this->club = $club;
    }

    public function getCapacity()
    {
        return $this->capacity;
    }

    public function setCapacity($capacity): void
    {
        $this->capacity = $capacity;
    }

    public function getClub(): Club
    {
        return $this->club;
    }
}
