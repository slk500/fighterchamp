<?php

declare(strict_types=1);

namespace AppBundle\Entity;

use AppBundle\Entity\Traits\TimestampableTrait;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="facebook")
 * @ORM\HasLifecycleCallbacks
 */
class Facebook
{
    use TimestampableTrait;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     * @var int
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     * @var string
     */
    private $name;

    /**
     * @ORM\Column(type="string")
     * @var string
     */
    private $surname;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @var string
     */
    private $email;

    /**
     * @ORM\Column(type="string", unique=true)
     * @var string
     */
    private $facebookId;

    /**
     * @ORM\Column(type="string")
     * @var bool
     */
    private $male;

    /**
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    public function __construct(string $facebookId, string $name, string $surname, ?string $email, bool $male)
    {
        $this->facebookId = $facebookId;
        $this->name = $name;
        $this->surname = $surname;
        $this->email = $email;
        $this->male = $male;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getSurname(): string
    {
        return $this->surname;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function getFacebookId(): string
    {
        return $this->facebookId;
    }

    public function isMale(): bool
    {
        return $this->male;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser($user): void
    {
        $this->user = $user;
    }
}
