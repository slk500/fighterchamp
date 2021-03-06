<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Fight;
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
     * @Route("/{id}", name="view_fight_show")
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
     * @Route("", name="view_fight_list")
     * @Method("GET")
     */
    public function listAction()
    {
        return $this->render('fight/list.html.twig');
    }
}
