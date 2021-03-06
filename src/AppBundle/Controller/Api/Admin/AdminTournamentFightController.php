<?php

namespace AppBundle\Controller\Api\Admin;

use AppBundle\Entity\Enum\UserFightResult;
use AppBundle\Entity\Fight;
use AppBundle\Entity\Tournament;
use AppBundle\Entity\UserFight;
use AppBundle\Service\FightService;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/api/admin")
 */
class AdminTournamentFightController extends Controller
{
    /**
     * @Route("/toggle-corner", name="api_fight_toggle_corners")
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
     * @Route("/fight/set-winner", name="api_fight_set_winner")
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
     * @Route("/{id}/fight/change-position-fight", name="api_fight_change_position")
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
}
