<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Discipline;
use AppBundle\Entity\FightProposition;
use AppBundle\Entity\Place;
use AppBundle\Entity\Tournament;
use AppBundle\Form\FightPropositionType;
use AppBundle\Form\SparringCreateType;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/turnieje")
 */
class TournamentController extends Controller
{
    /**
     * @Route("", name="view_tournament_list")
     */
    public function list(EntityManagerInterface $em): Response
    {
        $officialTournaments = $em->getRepository(Tournament::class)
            ->findBy(['isEditable' => false], ['start' => 'DESC']);

        $unofficialTournaments = $em->getRepository(Tournament::class)
            ->findBy(['isEditable' => true], ['start' => 'DESC']);

        return $this->render(
            'tournament/list.twig',
            [
                'officialTournaments' => $officialTournaments,
                'unofficialTournaments' => $unofficialTournaments
            ]
        );
    }

    /**
     * @Route("/{id}", requirements={"id": "\d+"}, name="view_tournament_show")
     */
    public function show(Tournament $tournament)
    {
        return $this->render('tournament/show.twig',
            [
                'tournament' => $tournament,
            ]
        );
    }

    /**
     * @Route("/{id}/regulamin", name="view_tournament_rules")
     */
    public function rules(Tournament $tournament)
    {
        return $this->render(
            "tournament/rules.html.twig",
            [
                'tournament' => $tournament,
            ]
        );
    }

    /**
     * @Route("/{id}/kontakt", name="view_tournament_contact")
     */
    public function contact(Tournament $tournament)
    {
        return $this->render(
            'tournament/contact.html.twig',
            [
                'tournament' => $tournament,
            ]
        );
    }

    /**
     * @Route("/{id}/walki", name="view_tournament_fights")
     */
    public function fights(Tournament $tournament)
    {
        return $this->render('tournament/fights.html.twig', [
            'tournament' => $tournament
        ]);
    }
}
