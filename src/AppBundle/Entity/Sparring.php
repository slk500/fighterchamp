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
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\SignupSparring", mappedBy="sparring")
     */
    private $signups;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $start;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $createdAt;

    /**
     * @ORM\ManyToMany(targetEntity="Discipline", mappedBy="sparrings")
     */
    private $disciplines;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Place")
     */
    private $place;

    /**
     * @var string
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    public function __construct()
    {
        $this->createdAt = \DateTime::createFromFormat('U', (string) time());
        $this->signups = new ArrayCollection();
        $this->disciplines = new ArrayCollection();
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

    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    public function addDiscipline(Discipline $discipline): void
    {
        if (!$this->disciplines->contains($discipline)) {
            $this->disciplines->add($discipline);
        }

        $this->disciplines->add($discipline);
        $discipline->addSparring($this);
    }

    public function removeDiscipline(Discipline $discipline)
    {
        $this->disciplines->removeElement($discipline);
        $discipline->removeSparring($this);
    }

    public function getDisciplines(): Collection
    {
        return $this->disciplines;
    }

    public function getPlace()
    {
        return $this->place;
    }

    public function setPlace(Place $place): void
    {
        $this->place = $place;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }
}
