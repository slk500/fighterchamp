<?php

namespace AppBundle\Controller\Api;

use AppBundle\Entity\SignupSparring;
use Doctrine\ORM\EntityManagerInterface;

class SignupSparringController
{
    public function delete(SignupSparring $signup, EntityManagerInterface $entityManager)
    {
        $entityManager->remove($signup);
        $entityManager->flush();
    }
}
