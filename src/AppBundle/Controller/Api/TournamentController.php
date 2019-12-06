<?php

namespace AppBundle\Controller\Api;

use AppBundle\Entity\Tournament;

class TournamentController
{
    public function show(Tournament $tournament)
    {
        return $tournament;
    }

    public function showFights(Tournament $tournament)
    {
        return $tournament->getFightsReady();
    }
}
