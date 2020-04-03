<?php

namespace AppBundle\Controller\Api;

use AppBundle\Entity\Fight;
use AppBundle\Entity\SignupTournament;
use AppBundle\Repository\FightRepository;
use AppBundle\Service\FightService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class FightController
{
    public function create(Request $request, FightService $fightService, EntityManagerInterface $entityManager)
    {
        $parameterBag = $request->request;
        $signupOneId = $parameterBag->get('signup_1');
        $signupTwoId = $parameterBag->get('signup_2');

        $signUpRepo = $entityManager->getRepository(SignupTournament::class);
        $signUp0 = $signUpRepo->find($signupOneId);
        $signUp1 = $signUpRepo->find($signupTwoId);

        $fight = $fightService->createFightFromSignUps($signUp0, $signUp1);

        return $fight;
    }

    public function show(Fight $fight)
    {
        return $fight;
    }

    public function list(FightRepository $fightRepository)
    {
        return $fightRepository->findBy(
            ['isVisible' => true],
            ['position'=>'ASC']
        );
    }

    public function delete(Fight $fight, EntityManagerInterface $entityManager)
    {
        $entityManager->remove($fight);
        $entityManager->flush();
    }
}
