<?php

namespace AppBundle\Controller\Api;

use AppBundle\Entity\SparringProposition;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class SparringPropositionController
{
    public function update(SparringProposition $sparringProposition,
                           Request $request, EntityManagerInterface $entityManager)
    {
        $parameterBag = $request->request;
        $sparringProposition->status = $parameterBag->get('status');
        $entityManager->flush();

        return $sparringProposition;
    }
}
