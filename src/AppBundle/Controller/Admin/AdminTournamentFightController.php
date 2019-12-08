<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\Enum\UserFightResult;
use AppBundle\Entity\Fight;
use AppBundle\Entity\SignUpTournament;
use AppBundle\Entity\Tournament;
use AppBundle\Entity\UserFight;
use AppBundle\Service\FightService;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/admin")
 */
class AdminTournamentFightController extends Controller
{
    /**
     * @Route("/turnieje/{id}/walki", name="admin_tournament_fights")
     */
    public function listAction(Tournament $tournament, EntityManagerInterface $em, SerializerInterface $serializer)
    {
        $fights = $em->getRepository('AppBundle:Fight')
            ->findAllFightsForTournamentAdmin($tournament);

        $freeSignUpIds = $this->getDoctrine()
            ->getRepository('AppBundle:SignUpTournament')->findAllDeletedWhichHaveAFight($tournament->getId());

        $signUps = [];

        foreach ($freeSignUpIds as $signUp) {
            $signUps [] = $this->getDoctrine()->getRepository('AppBundle:SignUpTournament')->find($signUp['id']);
        }

        $normalizeSignUps = $serializer->normalize($signUps);

        return $this->render('admin/fight.html.twig', [
            'fights' => $fights,
            'tournament' => $tournament,
            'signUps' => $normalizeSignUps
        ]);
    }

    /**
     * @Route("/turnieje/{id}/parowanie", name="admin_tournament_pair")
     * @Method("GET")
     */
    public function pairAction(Tournament $tournament, SerializerInterface $serializer)
    {
        $freeSignUpIds = $this->getDoctrine()
            ->getRepository('AppBundle:SignUpTournament')->findAllSignUpButNotPairYet($tournament->getId());

        $signUps = [];

        foreach ($freeSignUpIds as $signUp) {
            $signUps [] = $this->getDoctrine()->getRepository('AppBundle:SignUpTournament')->find($signUp['id']);
        }
        //todo should be one query

        return $this->render(':admin:pair.html.twig', array(
            'freeUsers' => $serializer->normalize($signUps),
            'tournament' => $tournament
        ));
    }
}
