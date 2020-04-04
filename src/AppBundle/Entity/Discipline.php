<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="discipline")
 */
class Discipline
{
    /**
     * @var int
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=false)
     */
    private $name;

    /**
     * @var Collection
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Club", inversedBy="disciplines")
     */
    private $clubs;

    /**
     * @var Collection
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Tournament", inversedBy="disciplines")
     */
    private $tournaments;

    public function __construct(string $name)
    {
        $this->name = $name;
        $this->clubs = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function __toString(): string
    {
        return $this->name;
    }

    public function addClub(Club $club)
    {
        $this->clubs->add($club);
    }

    public function removeClub(Club $club)
    {
        $this->clubs->removeElement($club);
    }

    public function addTournament(Tournament $tournament)
    {
        $this->tournaments->add($tournament);
    }

    public function removeTournament(Tournament $tournament)
    {
        $this->tournaments->removeElement($tournament);
    }
}
