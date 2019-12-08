<?php

namespace AppBundle\Controller\Api;

use AppBundle\Entity\SignUpTournament;
use AppBundle\Entity\Tournament;
use AppBundle\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class SignUpController extends Controller
{
    public function show(SignUpTournament $signUp)
    {
        return $signUp;
    }

    public function create(Request $request, EntityManagerInterface $entityManager)
    {
        $userId = $request->request->get('userId');
        $formula = $request->request->get('formula');
        $weight = $request->request->get('weight');
        $tournamentId = $request->request->get('tournamentId');

        $user = $entityManager->getReference(User::class, $userId);
        $tournament = $entityManager->getReference(Tournament::class, $tournamentId);

        $signupTournament = new SignUpTournament($user, $tournament);
        $signupTournament->setFormula($formula);
        $signupTournament->setWeight($weight);

        $entityManager->persist($signupTournament);
        $entityManager->flush();

        return $signupTournament;
    }

    public function list(Tournament $tournament, EntityManagerInterface $entityManager)
    {
        return $entityManager->getRepository(SignUpTournament::class) //todo remove flags delete
            ->findBy(
                [
                    'tournament' => $tournament,
                    'deleted_at' => null,
                    'deletedAtByAdmin' => null
                ]
            );
    }

    public function listNotPair(Tournament $tournament, EntityManagerInterface $entityManager)
    {
        $freeSignUpIdsRaw = $entityManager
            ->getRepository('AppBundle:SignUpTournament')->findAllSignUpButNotPairYet($tournament->getId());

        $freeSignUpIds = array_map(function (array $arr) {
            return $arr['id'];
        }, $freeSignUpIdsRaw);

        return $this->getDoctrine()
            ->getRepository('AppBundle:SignUpTournament')
            ->findBy(['id' => $freeSignUpIds, 'deletedAtByAdmin' => null]);
    }

    public function delete(SignUpTournament $signUpTournament, EntityManagerInterface $entityManager)
    {
        $signUpTournament->delete();
        $entityManager->flush();

        return $signUpTournament;
    }
}
