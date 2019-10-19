<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\Fight;
use AppBundle\Entity\Tournament;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/admin")
 */
class AdminTournamentWeight extends Controller
{
    /**
     * @Route("/set-weighted", name="set_weighted")
     */
    public function setWeighted(Request $request, EntityManagerInterface $em)
    {
        $signUpId = $request->request->get('signUpId');
        $weighted = $request->request->get('weighted');

        $signUp = $em->getRepository('AppBundle:SignUpTournament')
            ->find($signUpId);

        $signUp->setWeighted($weighted);

        //TODO naprawić powinien pytać czy ma rozparować walkę a nie robić to automatycznie
//        $tournament = $em->getRepository('AppBundle:Tournament')->find(4);
//
//        if($weighted != $signUp->getWeight()){
//
//            $fights = $em->getRepository('AppBundle:Fight')
//                ->findUserFightsInTournament($signUp->getUser(), $tournament );
//
//            if($fights){
//                foreach($fights as $fight){
//
//                    $users = $fight->getUsers();
//
//                    $em->remove($fight);
//
//                    $userOne = $users[0]->getUser();
//                    $userTwo = $users[1]->getUser();
//
//                    $this->addFlash('warning', "Walka $userOne vs. $userTwo została rozparowana ");
//
//                }
//            }
//        }

        $em->flush();




        return new Response(200);
    }
}
