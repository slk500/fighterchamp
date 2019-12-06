<?php

namespace AppBundle\Controller\Api;

use AppBundle\Repository\ClubRepository;

class ClubController
{
    public function list(ClubRepository $clubRepository)
    {
        return $clubRepository->findAll();
    }
}
