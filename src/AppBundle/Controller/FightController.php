<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Fight;
use AppBundle\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/walki")
 */
class FightController extends Controller
{
    /**
     * @Route("/{id}", name="fight_show")
     * @Method("GET")
     */
    public function showAction(Fight $fight, SerializerInterface $serializer)
    {
        return $this->render(
            'fight/show.html.twig',
            [
                'fight' => $serializer->normalize($fight)
            ]
        );
    }

    /**
     * @Route("", name="fight_list")
     */
    public function listAction()
    {
        return $this->render('fight/list.html.twig');
    }
}
