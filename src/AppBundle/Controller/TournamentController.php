<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Tournament;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/turnieje")
 */
class TournamentController extends Controller
{
    /**
     * @Route("", name="tournament_list")
     */
    public function listAction(EntityManagerInterface $em): Response
    {
        $tournaments = $em->getRepository(Tournament::class)
            ->findBy([], ['id' => 'DESC']);


        return $this->render(
            'tournament/list.twig',
            [
                'tournaments' => $tournaments,
            ]
        );
    }

    /**
     * @Route("/{id}", name="tournament_show")
     */
    public function showAction(Tournament $tournament)
    {
        return $this->render(
            'tournament/show.twig',
            [
                'tournament' => $tournament,
            ]
        );
    }

    /**
     * @Route("/{id}/regulamin", name="tournament_rules")
     */
    public function rulesAction(Tournament $tournament)
    {
        return $this->render(
            "tournament/rules.html.twig",
            [
                'tournament' => $tournament,
            ]
        );
    }

    /**
     * @Route("/{id}/kontakt", name="tournament_contact")
     */
    public function contactAction(Tournament $tournament)
    {
        return $this->render(
            'tournament/contact.html.twig',
            [
                'tournament' => $tournament,
            ]
        );
    }
}
