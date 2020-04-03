<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\SignupTournament;
use AppBundle\Entity\Tournament;
use AppBundle\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * @Route("/admin")
 */
class AdminTournamentSignUp extends Controller
{
    private function getAgeAtTournament($birthday, $tournamentDay)
    {
        if (!$birthday || !$tournamentDay instanceof \DateTime) {
            return null;
        }

        $diff = $tournamentDay->diff($birthday);

        return $diff->y;
    }

    /**
     * @Route("/turnieje/{id}/lista", name="view_admin_tournament_signup")
     */
    public function signUp(Tournament $tournament)
    {
        $signUpsTournament = $this->getDoctrine()
            ->getRepository(SignupTournament::class)
            ->findAllForTournament($tournament);

        $signUpsPaid = $signUpsTournament->filter(
            fn(SignupTournament $signupTournament) => $signupTournament->isPaid())
            ->count();

        $signUpsDeleted = $this->getDoctrine()
            ->getRepository(SignupTournament::class)->signUpsDeleted($tournament);

        $howManyWeighted = $signUpsTournament->filter(
            fn(SignupTournament $signupTournament) => $signupTournament->getWeighted())
            ->count();

        $weights = $this->getDoctrine()
            ->getRepository('AppBundle:Ruleset')
            ->getWeight();

        $seniors = 0;
        $seniorsPaid = 0;
        $juniors = 0;
        $juniorsPaid = 0;

        foreach ($signUpsTournament as $signUp) {
            $user = $signUp->getUser();

            $age = $this->getAgeAtTournament($user->getBirthDay(), $tournament->getStart());

            if ($age > 18) {
                $seniors++;
                if ($signUp->isPaid()) {
                    $seniorsPaid++;
                }
            } else {
                $juniors++;
                if ($signUp->isPaid()) {
                    $juniorsPaid++;
                }
            }
        }

        $finance = [
            'seniors' => $seniors,
            'seniorsPaid' => $seniorsPaid,
            'juniors' => $juniors,
            'juniorsPaid' => $juniorsPaid
        ];

        return $this->render(':admin/sign-up:list.html.twig', [
            'tournament' => $tournament,
            'signUpsTournament' => $signUpsTournament,
            'signUpsPaid' => $signUpsPaid,
            'weights' => $weights,
            'howManyWeighted' => $howManyWeighted,
            'finance' => $finance,
            'signUpsDeleted' => $signUpsDeleted
        ]);
    }

    /**
     * @Route("/turnieje/{id}/lista/dodaj", name="view_admin_create_signup")
     */
    public function createSignUp(
        EntityManagerInterface $em,
        Tournament $tournament,
        NormalizerInterface $serializer
    ) {
        $users = $em->getRepository(User::class)
            ->findBy([], ['surname' => 'asc']);

        $weights = $em->getRepository('AppBundle:Ruleset')
            ->getWeight();

        return $this->render('admin/sign-up/create.html.twig', [
            'users' => $users,
            // $serializer->normalize($users),
            'weights' => $weights,
            'tournament' => $tournament
        ]);
    }
}
