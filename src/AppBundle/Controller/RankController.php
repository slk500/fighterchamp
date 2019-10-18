<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Repository\RankRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class RankController extends Controller
{
    /**
     * @Route("ranking/{year}/{age}", name="rank")
     */
    public function indexAction(
        RankRepository $rankRepository,
        string $year = 'wszystkie-lata',
        string $age = 'wszystkie-kategorie-wiekowe'
    ) {
        $params = [
        'year' => $year,
        'age' => $age
      ];

        $users = $rankRepository->findAllByYearAndAge($params);

        return $this->render('rank/rank.html.twig', [
            'users' => $users,
            'year' => $year,
            'age' => $age
        ]);
    }
}
