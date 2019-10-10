<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Tournament;
use AppBundle\Form\SignUpTournamentType;
use AppBundle\Entity\SignUpTournament;
use AppBundle\Util\AgeCategoryConverter;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Serializer\SerializerInterface;

//todo REFACTOR split on create & update

/**
 * @Route("/turnieje")
 */
class TournamentSignUpController extends Controller
{
    /**
     * @Route("/{id}/zapisy", name="tournament_sign_up")
     */
    public function signUpAction(Tournament $tournament, Request $request, EntityManagerInterface $em)
    {
        if ($this->get('security.authorization_checker')->isGranted('CAN_TOURNAMENT_ACTON', $tournament)) {

            $user = $this->getUser();

            $age = AgeCategoryConverter::convert($user->getBirthDay());

            $male = $user->getMale();
            $sex = ($male) ? "male" : "female";

            $traitChoices = $em->getRepository('AppBundle:Ruleset')
                ->findBy([$sex => true, $age => true],['weight' => 'ASC']);

            $arr = [];

            foreach ($traitChoices as $key => $value) {
                $arr = $arr + [$value->getWeight() => $value->getWeight()];
            }

            $isAlreadySignUp = $em->getRepository('AppBundle:SignUpTournament')
                ->findOneBy(
                    [
                        'tournament' => $tournament,
                        'user' => $user,
                        'deleted_at' => null
                    ]);

            $signupTournament = $isAlreadySignUp ?? new SignUpTournament($user, $tournament);

            $form = $this->createForm(SignUpTournamentType::class, $signupTournament,
                    ['trait_choices' => $arr]
                );

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {

                $signupTournament = $form->getData();

                if(!$isAlreadySignUp) {

                    $em->persist($signupTournament);

                }
                    $em->flush();

                return $this->redirectToRoute("tournament_sign_up", ['id' => $tournament->getId()]);
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