<?php

namespace AppBundle\Service;

use AppBundle\Entity\Fight;
use AppBundle\Entity\SignupTournament;
use AppBundle\Entity\UserFight;
use Doctrine\ORM\EntityManagerInterface;

class FightService
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function toggleCorners(Fight $fight): void
    {
        $fight->getUsersFight()->map(function (UserFight $userFight) {
            $userFight->changeCorner();
        });
    }

    public function createFightFromSignUps(SignupTournament $signUp1, SignupTournament $signUp2): Fight
    {
        $formula = $this->getHighestFormula($signUp1, $signUp2);
        $weight = $this->getHighestWeight($signUp1, $signUp2);

        $fight = new Fight($formula, $weight);

        $userFight1 = new UserFight($signUp1->getUser(), $fight);
        $userFight1->setIsRedCorner(true);
        $userFight2 = new UserFight($signUp2->getUser(), $fight);

        $tournament = $signUp1->getTournament(); //todo should take both signUps

        $fight->setTournament($tournament);

        $numberOfFights = $tournament->getFights()->count();

        $fight->setPosition($numberOfFights + 1);
        $fight->setDay($tournament->getStart());
        $fight->setDiscipline(
            $this->getDiscipline($signUp1, $signUp2)
        );

        $this->entityManager->persist($fight);
        $this->entityManager->persist($userFight1);
        $this->entityManager->persist($userFight2);
        $this->entityManager->flush();

        return $fight;
    }

    public function getHighestFormula(SignupTournament $signUp0, SignupTournament $signUp1): string
    {
        return ($signUp0->getFormula() <= $signUp1->getFormula()) ? $signUp0->getFormula() : $signUp1->getFormula();
    }

    public function getHighestWeight(SignupTournament $signUp0, SignupTournament $signUp1): string
    {
        return ($signUp0->getWeight() >= $signUp1->getWeight()) ? $signUp0->getWeight() : $signUp1->getWeight();
    }

    public function getDiscipline(SignupTournament $signUp0, SignupTournament $signUp1): ?string
    {
        if ($signUp0->getDiscipline() === 'Boks'|| $signUp1->getDiscipline() === 'Boks') {
            return 'Boks';
        }

        return $signUp0->getDiscipline();
    }

    public function splitFightsBasedOnDay(array $fights): array
    {
        $result = [];
        foreach ($fights as $fight) {
            $current = $fight->getDay();

            $result[$current->format('Y-m-d')][] = $fight;
        }

        return array_values($result);
    }
}
