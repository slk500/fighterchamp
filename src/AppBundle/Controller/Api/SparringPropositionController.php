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

        if ($parameterBag->get('status')){
            $sparringProposition->status = $parameterBag->get('status');
        }

        if($parameterBag->get('result')){
            $sparringProposition->result =
                ($parameterBag->get('result') == 'null') ? null : $parameterBag->get('result');
        }

        $entityManager->flush();

        return $sparringProposition;
    }
}
