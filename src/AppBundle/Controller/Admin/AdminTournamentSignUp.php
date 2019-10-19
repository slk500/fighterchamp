<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\SignUpTournament;
use AppBundle\Entity\Tournament;
use AppBundle\Entity\User;
use AppBundle\Repository\SignUpTournamentRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
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
     * @Route("/turnieje/{id}/lista", name="admin_tournament_sign_up")
     */
    public function signUp(Tournament $tournament)
    {
        $signUpsTournament = $this->getDoctrine()
            ->getRepository(SignUpTournament::class)
            ->findAllForTournament($tournament);

        $signUpsPaid = $this->getSignUpsPaid($signUpsTournament);

        $signUpsDeleted = $this->getDoctrine()
            ->getRepository(SignUpTournament::class)->signUpsDeleted($tournament);

//        $fightsWhereFightersAreNotWeighted = $this->getDoctrine()
//            ->getRepository('AppBundle:Fight')
//            ->findAllTournamentFightsWhereFightersAreNotWeighted($tournament);

        $howManyWeighted = $this->howManyWeighted($signUpsTournament);

        $weights = $this->getDoctrine()
            ->getRepository('AppBundle:Ruleset')
            ->getWeight();

//        $form = $this->createForm(SignUpTournamentType::class, null,
//            ['trait_choices' => $weights]
//        );

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
//            'fightsWhereFightersAreNotWeighted' => $fightsWhereFightersAreNotWeighted
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


    /**
     * @Route("/set-is-paid", name="set_is_paid")
     */
    public function isPaid(Request $request, EntityManagerInterface $em)
    {
        $signUpId = $request->request->get('signUpId');
        $isPaid =  $request->request->get('isPaid');

        $signUp = $em->getRepository(SignUpTournament::class)
            ->find($signUpId);

        $signUp->setIsPaid($isPaid);

        $em->flush();

        return new Response(200);
    }

    /**
     * @Route("/sign-up-delete-by-admin/{id}", name="admin_tournament_toggle_delete_by_admin")
     */
    public function toggleDeleteByAdminAction(SignUpTournament $signUpTournament, EntityManagerInterface $em)
    {
        $signUpTournament->setDeleteByAdmin($signUpTournament->getDeletedAtByAdmin() ? null : new DateTime('now'));

        $em->flush();

        return $this->redirectToRoute('admin_tournament_pair', [
            'id' => $signUpTournament->getTournament()->getId()
        ]); //todo change to 200 and js reload page
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
