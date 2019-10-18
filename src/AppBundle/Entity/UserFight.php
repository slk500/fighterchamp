<?php

namespace AppBundle\Entity;

use AppBundle\Entity\Enum\UserFightResult;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="user_fight")
 */
class UserFight
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     * @var int
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="userFights")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Fight", inversedBy="usersFight", fetch="EAGER")
     * @ORM\JoinColumn(nullable=false)
     */
    private $fight;

    /**
     * @ORM\Column(type="boolean")
     * @var bool
     */
    private $isRedCorner = false;

    /**
     * @ORM\Column(type="string")
     */
    private $result = '';

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Award", mappedBy="userFight")
     */
    private $awards;

    public function __construct(User $user, Fight $fight)
    {
        $this->user = $user;
        $this->fight = $fight;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getResult(): string
    {
        return $this->result;
    }

    public function setResult(UserFightResult $result): void
    {
        $this->result = $result->getValue();
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getFight(): Fight
    {
        return $this->fight;
    }

    public function isRedCorner(): bool
    {
        return $this->isRedCorner;
    }

    public function getOpponentUserFight(): UserFight
    {
        $usersFight = $this->getFight()->getUsersFight();

        return $usersFight->filter(function (UserFight $userFight) {
            return  $this !== $userFight;
        })->first();
    }

    public function resetResult(): void
    {
        $this->result = '';
    }

    public function setIsRedCorner(bool $isRedCorner): void
    {
        $this->isRedCorner = $isRedCorner;
    }

    public function getAwards(): Collection
    {
        return $this->awards;
    }

    public function setAwards($awards): void
    {
        $this->awards = $awards;
    }

    public function changeCorner(): void
    {
        $this->isRedCorner = !$this->isRedCorner;
    }
}
