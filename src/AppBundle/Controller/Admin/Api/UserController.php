<?php

namespace AppBundle\Controller\Admin\Api;

use AppBundle\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class UserController extends Controller
{
    public function indexAction(EntityManagerInterface $em)
    {
        $users = $em->getRepository(User::class)->findAll();

        return $users;
    }
}
