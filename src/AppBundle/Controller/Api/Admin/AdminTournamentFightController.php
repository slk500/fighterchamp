<?php

namespace AppBundle\Controller\Api\Admin;

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
     * @Route("/set-is-licence", name="set_is_licence")
     */
    public function setLicence(Request $request, EntityManagerInterface $em)
    {
        $fightId = $request->request->get('fightId');
        $isLicence =  $request->request->get('isLicence');

        $fight = $em->getRepository(Fight::class)
            ->find($fightId);

        $fight->setLicence($isLicence);

        $em->flush();

        return new Response(200);
    }

    /**
     * @Route("/toggle-corner", name="toggle_corners")
     */
    public function toggleCornersAction(EntityManagerInterface $em, FightService $fightService, Request $request)
    {
        $fightId = $request->request->get('fightId');

        $fight = $em->getReference(Fight::class, $fightId);

        $fightService->toggleCorners($fight);

        $em->flush();

        return new Response(null, 200);
    }

    /**
     * @Route("/fights-not-weighted-remove", name="fights_not_weighted_remove")
     */
    public function removeFightsWithNotWeighted()
    {
        $em = $this->getDoctrine()->getManager();
        $tournament = $em->getRepository('AppBundle:Tournament')
            ->find(8);

        $fightsWhereFightersAreNotWeighted = $this->getDoctrine()
            ->getRepository('AppBundle:Fight')
            ->findAllTournamentFightsWhereFightersAreNotWeighted($tournament);

        foreach ($fightsWhereFightersAreNotWeighted as $fight) {
            $em->remove($fight);
            $em->flush();
        }

        $fights = $em->getRepository('AppBundle:Fight')
            ->findAllFightsForTournamentAdmin($tournament);
        $this->refreshFightPosition($fights);


        return $this->redirectToRoute('admin_view_tournament_signup', ['id' => $tournament->getId()]);
    }

    /**
     * @Route("/walki", name="admin_tournament_create_fight")
     * @Method("POST")
     */
    public function createFight(Request $request, FightService $fightService, EntityManagerInterface $entityManager)
    {
        $data = $request->request->all();

        $signUpRepo = $entityManager->getRepository(SignUpTournament::class);
        $signUp0 = $signUpRepo->find($data['ids'][0]);
        $signUp1 = $signUpRepo->find($data['ids'][1]);

        $fightService->createFightFromSignUps($signUp0, $signUp1);

        return new Response(null, 201);
    }

    /**
     * @Route("/walki", name="admin_remove_fight")
     * @Method("DELETE")
     */
    public function deleteFight(Request $request, EntityManagerInterface $entityManager)
    {
        $fightId = $request->request->get('fightId');

        $fightRepository = $entityManager->getRepository(Fight::class);

        $fight = $fightRepository->find($fightId);

        $entityManager->remove($fight);
        $entityManager->flush();

        $fights = $fightRepository->findAllFightsForTournamentAdmin($fight->getTournament());

        $this->refreshFightPosition($fights);


        return new Response(null, 204);
    }

    /**
     * @Route("/fight/set-winner", name="setWinner")
     */
    public function setWinnerAction(Request $request, EntityManagerInterface $em)
    {
        $fightId = $request->request->get('userFightId');
        $result = $request->request->get('result');

        $userFight1 = $em->getRepository(UserFight::class)->find($fightId);
        $userFight2 = $userFight1->getOpponentUserFight();

        switch ($result) {
            case 'reset':
                $userFight1->resetResult();
                $userFight2->resetResult();
                break;
            case 'win':
                $userFight1->setResult(UserFightResult::WIN());
                $userFight2->setResult(UserFightResult::LOSE());
                break;
            case 'win_ko':
                $userFight1->setResult(UserFightResult::WIN_KO());
                $userFight2->setResult(UserFightResult::LOSE());
                break;
            case 'draw':
                $userFight1->setResult(UserFightResult::DRAW());
                $userFight2->setResult(UserFightResult::DRAW());
                break;

            default:
                throw new \Exception('No fight result');
        }

        $em->flush();

        return new Response(200);
    }

    /**
     * @Route("/{id}/fight/change-position-fight", name="changePositionFight")
     */
    public function changeOrderFight(Request $request, Tournament $tournament)
    {
        $fightId = $request->request->get('fightId');
        $position_to_insert = $request->request->get('wantedPosition');
        $position_element_to_take = $request->request->get('position');

        $em = $this->getDoctrine()->getManager();

        $fight = $em->getRepository('AppBundle:Fight')->find($fightId);
        $fights = $em->getRepository('AppBundle:Fight')
            ->findAllFightByDayAdmin($tournament, $fight->getDay());

        $taken_element = array_splice($fights, $position_element_to_take - 1, 1);

        array_splice($fights, $position_to_insert - 1, 0, $taken_element);

        $this->refreshFightPosition($fights);

        return new Response(200);
    }

    /**
     * @Route("/fight/toggleready", name="toggleFightReady")
     */
    public function toggleFightReady(Request $request, EntityManagerInterface $em)
    {
        $fightId = $request->request->get('fightId');

        $fight = $em->getRepository('AppBundle:Fight')
            ->findOneBy(['id' => $fightId]);

        $fight->toggleReady();
        $em->flush();

        return new Response(200);
    }

    /**
     * @Route("/fight/setday", name="setDay")
     * @return Response
     */
    public function setDayAction(Request $request, EntityManagerInterface $em)
    {
        $fightId = $request->request->get('fightId');
        $day = $request->request->get('day');

        $fight = $em->getRepository(Fight::class)
            ->find($fightId);

        $sobota = (new \DateTime())
            ->setDate(2019, 6, 1);

        $niedziela = (new \DateTime())
            ->setDate(2019, 6, 2);

        if ($day == 'sob.') {
            $fight->setDay($sobota);
        } else {
            $fight->setDay($niedziela);
        }

        $tournament = $fight->getTournament();
        $fights = $em->getRepository('AppBundle:Fight')
            ->findAllFightByDayAdmin($tournament, $fight->getDay());

        $fight->setPosition(count($fights) + 1);

        $em->flush();

        $this->refreshFightPosition($fights);

        return new Response(200);
    }

    public function refreshFightPosition($fights): void
    {
        $em = $this->getDoctrine()->getManager();

        $i = 1;
        foreach ($fights as $fight) {

            /**@var Fight $fight */
            $fight->setPosition($i);
            $i++;
        }
        $em->flush();
    }

    /**
     * @Route("/{id}/setwalki", name="allFightsReady")
     */
    public function publishFights(Tournament $tournament, EntityManagerInterface $em)
    {
        $em->getRepository('AppBundle:Fight')->setAllFightsReady($tournament);

        return $this->redirectToRoute('admin_tournament_fights', ['id' => $tournament->getId()]);
    }
}
