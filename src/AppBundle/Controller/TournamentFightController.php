<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Tournament;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * @Route("/turnieje")
 */
class TournamentFightController extends Controller
{
    /**
     * @Route("/{id}/walki", name="tournament_fights")
     */
    public function resultAction(Tournament $tournament)
    {
        return $this->render('tournament/fights.html.twig', [
            'tournament' => $tournament
        ]);
    }
}
