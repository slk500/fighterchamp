<?php
declare(strict_types = 1);

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="sparring_proposition")
 */
class SparringProposition
{
    public function __construct()
    {
        $this->createdAt = \DateTime::createFromFormat('U', (string) time());
    }

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    public ?int $id = null;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Sparring")
     * @ORM\JoinColumn(nullable=true)
     */
    public Sparring $sparring;

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
    public ?string $status = 'zaproszenie oczekuje na akceptacje przeciwnika';

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    public ?string $result = '';

    /**
     * @ORM\Column(type="datetime")
     */
    public \DateTime $createdAt;
}
