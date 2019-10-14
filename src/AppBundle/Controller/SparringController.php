<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Sparring;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/sparingi")
 */
class SparringController extends Controller
{
    /**
     * @Route("", name="sparring_list")
     */
    public function list(EntityManagerInterface $entityManager)
    {
        $sparrings = $entityManager
            ->getRepository(Sparring::class)
            ->findAll();

        dump($sparrings);

        return $this->render('sparring/list.html.twig',
            [
                'sparrings' => $sparrings
            ]);
    }
}
