<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Club;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * @Route("/kluby")
 */
class ClubController extends Controller
{
    /**
     * @Route("", name="view_club_list")
     */
    public function listAction()
    {
        return $this->render('club/list.twig');
    }

    /**
     * @Route("/{id}", name="view_club_show", options={"expose"=true})
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAction(Club $club, NormalizerInterface $normalizer)
    {
        return $this->render(
            'club/show.twig',
            ['club' => $normalizer->normalize($club)]
        );
    }
}
