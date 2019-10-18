<?php

namespace AppBundle\Controller\Api;

use AppBundle\Entity\Fight;
use AppBundle\Entity\Tournament;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

class FightController extends Controller
{
    public function showAction(Fight $fight, SerializerInterface $serializer)
    {
        $result = $serializer->serialize($fight, 'json');

        return new Response($result, 200, ['Content-Type' => 'application/json']);
    }

    public function listAction(EntityManagerInterface $em, SerializerInterface $serializer)
    {
        $fights = $em->getRepository(Fight::class)
            ->findBy(['isVisible' => true], ['position'=>'ASC']);

        $result = $serializer->normalize($fights, 'json');

        return new JsonResponse(['data' => $result]);
    }
}
