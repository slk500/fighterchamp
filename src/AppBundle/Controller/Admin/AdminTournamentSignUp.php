<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\SignUpTournament;
use AppBundle\Entity\Tournament;
use AppBundle\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
     * @Route("/turnieje/{id}/lista", name="admin_view_tournament_signup")
     */
    public function signUp(Tournament $tournament)
    {
        $signUpsTournament = $this->getDoctrine()
            ->getRepository(SignUpTournament::class)
            ->findAllForTournament($tournament);

        $signUpsPaid = $this->getSignUpsPaid($signUpsTournament);

        $signUpsDeleted = $this->getDoctrine()
            ->getRepository(SignUpTournament::class)->signUpsDeleted($tournament);

        $howManyWeighted = $this->howManyWeighted($signUpsTournament);

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
     * @Route("/turnieje/{id}/lista/dodaj", name="admin_create_signUp")
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

    private function getSignUpsPaid($signUpsTournament): int
    {
        $signUpsPaid = 0;
        foreach ($signUpsTournament as $signUp) {
            if ($signUp->isPaid()) {
                $signUpsPaid++;
            }
        }
        return $signUpsPaid;
    }

    private function howManyWeighted($signUpsTournament): int
    {
        $howManyWeighted = 0;
        foreach ($signUpsTournament as $signUp) {
            if ($signUp->getWeighted() != null) {
                $howManyWeighted++;
            }
        }
        return $howManyWeighted;
    }
}
