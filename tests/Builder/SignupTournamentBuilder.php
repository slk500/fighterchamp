<?php

namespace Tests\Builder;

use AppBundle\Entity\SignUpTournament;
use AppBundle\Entity\Tournament;
use AppBundle\Entity\User;

class SignupTournamentBuilder extends Builder
{
    /**
     * @var User
     */
    private $user;

    /**
     * @var Tournament
     */
    private $tournament;

    /**
     * @var string
     */
    private $weight;

    /**
     * @var string
     */
    private $formula;

    public function build(): SignUpTournament
    {
        $signup = new SignUpTournament($this->user, $this->tournament);
        $signup->setWeight('100');
        $signup->setFormula('boks');
        return $signup;
    }

    public function withUser(User $user): self
    {
        $this->user = $user;
        return $this;
    }

    public function withTournament(Tournament $tournament): self
    {
        $this->tournament = $tournament;
        return $this;
    }

    public function withWeight(string $weight): self
    {
        $this->weight = $weight;
        return $this;
    }

    public function withFormula(string $formula): self
    {
        $this->formula = $formula;
        return $this;
    }
}
