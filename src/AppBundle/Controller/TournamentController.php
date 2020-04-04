<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Discipline;
use AppBundle\Entity\Place;
use AppBundle\Entity\Tournament;
use AppBundle\Form\TournamentCreateType;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/turnieje")
 */
class TournamentController extends Controller
{
    /**
     * @Route("/dodaj", name="view_tournament_create")
     */
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TournamentCreateType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $array = $form->getData();

            $tournament = new Tournament();

            $tournament->setName($array['name']);
            $array['disciplines']->map(function (Discipline $discipline) use ($tournament) {
                $tournament->addDiscipline($discipline);
           });

            $tournament->setStart($array['start']);
            $tournament->setEnd($array['end']);

            if (array_key_exists('place', $array) ||
                array_key_exists('city', $array) ||
                array_key_exists('street', $array)
            ) {
                $place = new Place();
                $array['name'] ?? $place->setName($array['name']);
                $array['city'] ?? $place->setCity($array['city']);
                $array['street'] ?? $place->setStreet($array['street']);

                $tournament->setPlace($place);
            }
            $entityManager->persist($place);
            $entityManager->persist($tournament);
            $entityManager->flush();

            $link = $this->generateUrl('view_tournament_show', ['id' => $tournament->getId()]);
            $this->addFlash('success', 'Sukces! Dodałeś turniej: ' . "<a href='$link'>{$tournament->getName()}</a>");

            return $this->redirect($link);
        }

        return $this->render('tournament/create.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("", name="view_tournament_list")
     */
    public function list(EntityManagerInterface $em): Response
    {
        $officialTournaments = $em->getRepository(Tournament::class)
            ->findBy(['isEditable' => false], ['start' => 'DESC']);

        $unofficialTournaments = $em->getRepository(Tournament::class)
            ->findBy(['isEditable' => true], ['start' => 'DESC']);

        return $this->render(
            'tournament/list.twig',
            [
                'officialTournaments' => $officialTournaments,
                'unofficialTournaments' => $unofficialTournaments
            ]
        );
    }

    /**
     * @Route("/{id}", requirements={"id": "\d+"}, name="view_tournament_show")
     */
    public function show(Tournament $tournament)
    {
        return $this->render(
            'tournament/show.twig',
            [
                'tournament' => $tournament,
            ]
        );
    }

    /**
     * @Route("/{id}/regulamin", name="view_tournament_rules")
     */
    public function rules(Tournament $tournament)
    {
        return $this->render(
            "tournament/rules.html.twig",
            [
                'tournament' => $tournament,
            ]
        );
    }

    /**
     * @Route("/{id}/kontakt", name="view_tournament_contact")
     */
    public function contact(Tournament $tournament)
    {
        return $this->render(
            'tournament/contact.html.twig',
            [
                'tournament' => $tournament,
            ]
        );
    }

    /**
     * @Route("/{id}/walki", name="view_tournament_fights")
     */
    public function fights(Tournament $tournament)
    {
        return $this->render('tournament/fights.html.twig', [
            'tournament' => $tournament
        ]);
    }
}
