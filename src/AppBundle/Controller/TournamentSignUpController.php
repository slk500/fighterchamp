<?php

namespace AppBundle\Controller;

use AppBundle\Entity\SignUpTournament;
use AppBundle\Entity\Tournament;
use AppBundle\Form\SignUpTournamentType;
use AppBundle\Service\RulesetService;
use AppBundle\Util\AgeCategoryConverter;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

//todo REFACTOR split on create & update

/**
 * @Route("/turnieje")
 */
class TournamentSignUpController extends Controller
{
    /**
     * @Route("/{id}/zapisy", name="view_tournament_signup")
     */
    public function signUpAction(Tournament $tournament, Request $request, EntityManagerInterface $em, RulesetService $rulesetService)
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_USER') && ($this->getUser())->getType() != 3) {
            $user = $this->getUser();

            $weights = $rulesetService->getWeights($em, $user);

            $isAlreadySignUp = $em->getRepository('AppBundle:SignUpTournament')
                ->findOneBy(
                    [
                        'tournament' => $tournament,
                        'user' => $user,
                        'deleted_at' => null
                    ]
                );

            $signupTournament = $isAlreadySignUp ?? new SignUpTournament($user, $tournament);

            $form = $this->createForm(
                SignUpTournamentType::class,
                $signupTournament,
                ['trait_choices' => $weights]
            );

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $signupTournament = $form->getData();

                if (!$isAlreadySignUp) {
                    $em->persist($signupTournament);
                }
                $em->flush();

                return $this->redirectToRoute("view_tournament_signup", ['id' => $tournament->getId()]);
            }

            return $this->render('tournament/sign_up.twig', [
                'form' => $form->createView(),
                'tournament' => $tournament,
                'isUserRegister' => $isAlreadySignUp,
            ]);
        }

        return $this->render('tournament/sign_up.twig', [
            'tournament' => $tournament,
            'isUserRegister' => null,
        ]);
    }
}
