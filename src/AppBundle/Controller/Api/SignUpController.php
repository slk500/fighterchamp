<?php

namespace AppBundle\Controller\Api;

use AppBundle\Entity\SignUpTournament;
use AppBundle\Entity\Tournament;
use AppBundle\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class SignUpController extends Controller
{
    public function showAction(SignUpTournament $signUp)
    {
        return $signUp;
    }

    public function newAction(Request $request, EntityManagerInterface $entityManager)
    {
        $userId = $request->request->get('userId');
        $formula = $request->request->get('formula');
        $weight = $request->request->get('weight');
        $tournamentId = $request->request->get('tournamentId');

        $user = $entityManager->getReference(User::class, $userId);
        $tournament = $entityManager->getReference(Tournament::class, $tournamentId);

        $signUpTournament = new SignUpTournament($user, $tournament);
        $signUpTournament->setFormula($formula);
        $signUpTournament->setWeight($weight);

        $entityManager->persist($signUpTournament);
        $entityManager->flush();

        return new Response();
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
            ->findBy(['id' => $freeSignUpIds]);
    }

    public function delete(SignUpTournament $signUpTournament, EntityManagerInterface $entityManager, NormalizerInterface $normalizer)
    {
        $signUpTournament->delete();
        $entityManager->flush();

        return $signUpTournament;
    }
}
