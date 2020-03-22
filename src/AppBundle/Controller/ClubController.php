<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Club;
use AppBundle\Form\ClubType;
use AppBundle\Repository\ClubRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
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
     * @Route("/{id}", requirements={"id": "\d+"}, name="view_club_show", options={"expose"=true})
     */
    public function showAction(Club $club, NormalizerInterface $normalizer)
    {
        return $this->render(
            'club/show.twig',
            ['club' => $normalizer->normalize($club)]
        );
    }

    /**
     * @Route("/{id}/edytuj", requirements={"id": "\d+"}, name="view_club_update", options={"expose"=true})
     */
    public function update(Request $request, EntityManagerInterface $entityManager, ClubRepository $clubRepository, Club $club)
    {
        $form = $this->createForm(ClubType::class, $club);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->flush();

            $this->addFlash('success', 'Sukces! Twoje zmiany zostały zapisane');
            return $this->redirect(
                $this->generateUrl('view_homepage')
            );
        }

        return $this->render(
            'club/update.twig',
            [
                'form' => $form->createView(),
                'clubs' => $clubRepository->findAll(),
                'club' => $club
            ]
        );
    }

    /**
     * @Route("/utworz", name="view_club_create", options={"expose"=true})
     */
    public function create(Request $request, EntityManagerInterface $entityManager, ClubRepository $clubRepository)
    {
        $form = $this->createForm(ClubType::class, null);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $club = $form->getData();
            $entityManager->persist($club);
            $entityManager->flush();

            $link = $this->generateUrl('view_club_show', ['id' => $club->getId()]);
            $this->addFlash('success', 'Sukces! Utworzyłeś nowy klub: ' . "<a href='$link'>{$club->getName()}</a>");

            return $this->redirect(
                $this->generateUrl('view_homepage')
            );
        }

        return $this->render(
            'club/create.twig',
            [
                'form' => $form->createView(),
                'clubs' => $clubRepository->findAll(),
                'pageTitle' => 'Utwórz nowy klub'
            ]
        );
    }
}
