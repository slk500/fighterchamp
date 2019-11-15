<?php


namespace AppBundle\Controller\Admin\Api;

use AppBundle\Entity\Tournament;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class TournamentController extends Controller
{
    public function list(EntityManagerInterface $em)
    {
        $tournaments = $em->getRepository(Tournament::class)->findAllForAdmin();

        return $tournaments;
    }
}
