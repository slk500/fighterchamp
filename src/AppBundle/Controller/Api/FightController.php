<?php

namespace AppBundle\Controller\Api;

use AppBundle\Entity\Fight;
use AppBundle\Repository\FightRepository;

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
}
