<?php

namespace AppBundle\Controller;

use AppBundle\Repository\ClubRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class HomepageController extends Controller
{
    /**
     * @Route("/", name="view_homepage")
     */
    public function resultAction(ClubRepository $clubRepository)
    {
        return $this->render(':main:homepage.html.twig',
            [
                'clubs' => $clubRepository->findAll()
            ]
        );
    }
}
