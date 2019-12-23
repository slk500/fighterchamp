<?php

namespace AppBundle\Controller\Api;

use AppBundle\Entity\Fight;
use AppBundle\Repository\FightRepository;
use Doctrine\ORM\EntityManagerInterface;

class FightController
{
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
