<?php
declare(strict_types = 1);

namespace AppBundle\Entity;

use AppBundle\Entity\Traits\TimestampableTrait;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="fight_proposition")
 */
class FightProposition
{
    use TimestampableTrait;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Tournament")
     * @ORM\JoinColumn(nullable=true)
     * @var Tournament
     */
    public Tournament $tournament;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     */
    public ?User $user = null;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     */
    public ?User $opponent = null;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    public ?string $formula = null;

    /**
     * @ORM\Column(type="string")
     */
    public ?string $weight = null;

    /**
     * @ORM\Column(type="string")
     */
    public ?string $discipline = null;

    /**
     * @ORM\Column(type="string")
     */
    public ?string $result = null;

    public function __construct(?User $user, Tournament $tournament)
    {
        $this->tournament = $tournament;
        $this->user = $user;
    }
}
