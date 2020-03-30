<?php

declare(strict_types = 1);

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="club")
 */
class Club
{
    /**
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\User", mappedBy="club")
     */
    private $users;

    /**
     * @ORM\ManyToMany(targetEntity="Discipline", mappedBy="clubs")
     */
    private $disciplines;

    /**
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(name="city", type="string", length=255, nullable=true)
     */
    private $city;

    /**
     * @var string
     *
     * @ORM\Column(name="street", type="string", length=255, nullable=true)
     */
    private $street;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $www;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $imageName;

    /**
     * @var string
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $lat;

    /**
     * @var string
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $lng;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $createdAt;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->users = new ArrayCollection();
        $this->disciplines = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLat(): ?string
    {
        return $this->lat;
    }

    public function setLat(?string $lat): void
    {
        $this->lat = $lat;
    }

    public function getLng(): ?string
    {
        return $this->lng;
    }

    public function setLng(?string $lng): void
    {
        $this->lng = $lng;
    }

    public function setName(?string $name)
    {
        $this->name = $name;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setCity(?string $city)
    {
        $this->city = $city;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setStreet(?string $street)
    {
        $this->street = $street;
    }

    public function getStreet(): ?string
    {
        return $this->street;
    }

    public function getImageName(): ?string
    {
        return $this->imageName;
    }

    public function setImageName(?string $imageName)
    {
        $this->imageName = $imageName;
    }

    public function getWww(): ?string
    {
        return $this->www;
    }

    public function setWww(?string $www)
    {
        $this->www = $www;
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    /**
     * @return User[]|Collection
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function __toString()
    {
        return $this->name;
    }

    /**
     * @return Discipline[]|Collection
     */
    public function getDisciplines(): Collection
    {
        return $this->disciplines;
    }

    public function addDiscipline(Discipline $discipline): void
    {
        if (!$this->disciplines->contains($discipline)) {
            $this->disciplines->add($discipline);
        }

        $this->disciplines->add($discipline);
        $discipline->addClub($this);
    }

    public function removeDiscipline(Discipline $discipline)
    {
        $this->disciplines->removeElement($discipline);
        $discipline->removeClub($this);
    }
}
