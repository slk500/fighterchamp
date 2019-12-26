<?php

namespace Tests\Builder;

use AppBundle\Entity\Tournament;

class TournamentBuilder extends Builder
{
    public const DEFAULT_NAME = 'DefaultName';

    /**
     * @var string
     */
    private $name = self::DEFAULT_NAME;

    public function build(): Tournament
    {
        $tournament = new Tournament();
        $tournament->setName($this->name);
        $tournament->setStart(new \DateTime());
        $tournament->setCapacity(10);
        $tournament->setDiscipline('Boks');

        return $tournament;
    }

    public function withName(string $name): self
    {
        $this->name = $name;
        return $this;
    }
}
