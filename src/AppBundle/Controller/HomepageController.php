<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Tournament;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class HomepageController extends Controller
{
    /**
     * @Route("/", name="view_homepage")
     */
    public function resultAction(EntityManagerInterface $em)
    {
        $tournament = $em->getRepository(Tournament::class)
            ->findNewestOne();

        return $this->render(':main:homepage.html.twig', [
            'tournament' => $tournament,
            'tournamentId' => isset($tournament) ? $tournament->getId() : 0
        ]);
    }
}
