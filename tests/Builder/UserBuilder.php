<?php

namespace Tests\Builder;

use AppBundle\Entity\User;

class UserBuilder extends Builder
{
    public const DEFAULT_NAME = 'DefaultName';
    public const DEFAULT_SURNAME = 'DefaultSurname';
    public const DEFAULT_PASSWORD = 'password';
    public const DEFAULT_EMAIL = 'default@mail.com';
    public const DEFAULT_TYPE = User::TYPE_FIGHTER;
    public const DEFAULT_BIRTHDAY = '1986-01-08';

    /**
     * @var string
     */
    private $name = self::DEFAULT_NAME;

    /**
     * @var string
     */
    private $surname = self::DEFAULT_SURNAME;

    /**
     * @var int
     */
    private $type = self::DEFAULT_TYPE;

    /**
     * @var string
     */
    private $password = self::DEFAULT_PASSWORD;

    /**
     * @var string
     */
    private $email = self::DEFAULT_EMAIL;

    /**
     * @var string
     */
    private $birthday = self::DEFAULT_BIRTHDAY;

    public function build(): User
    {
        $user = new User();
        $user->setName($this->name);
        $user->setSurname($this->surname);
        $user->setHash($this->faker->sha1);
        $user->setType($this->type);
        $user->setPlainPassword($this->password);
        $user->setEmail($this->email);
        $user->setBirthDay(
            \DateTime::createFromFormat('Y-m-d', $this->birthday)
        );
        $user->setPesel('86010800000');

        return $user;
    }

    public function withName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function withSurname(string $surname): self
    {
        $this->surname = $surname;
        return $this;
    }

    public function withType(int $type): self
    {
        $this->type = $type;
        return $this;
    }

    public function withPassword(string $password): self
    {
        $this->password = $password;
        return $this;
    }

    public function withEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }

    public function withBirthday(\DateTime $birthday)
    {
        $this->birthday = $birthday;
        return $this;
    }
}
