<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Discipline;
use AppBundle\Entity\Place;
use AppBundle\Entity\SignupSparring;
use AppBundle\Entity\Sparring;
use AppBundle\Entity\SparringProposition;
use AppBundle\Form\SignupSparringType;
use AppBundle\Form\SparringCreateType;
use AppBundle\Form\SparringPropositionType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/sparingi")
 */
class SparringController extends Controller
{
    /**
     * @Route("/dodaj", name="view_sparring_create")
     */
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(SparringCreateType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $array = $form->getData();

            $sparring = new Sparring();

            $sparring->setName($array['name']);
            $array['disciplines']->map(function (Discipline $discipline) use ($sparring) {
                $sparring->addDiscipline($discipline);
            });

            $sparring->setStart($array['start']);

            if (array_key_exists('place', $array) ||
                array_key_exists('city', $array) ||
                array_key_exists('street', $array)
            ) {
                $place = new Place();
                if ($array['place']) $place->setName($array['place']);
                if ($array['city']) $place->setCity($array['city']);
                if ($array['street']) $place->setStreet($array['street']);

                $sparring->setPlace($place);
            }
            $entityManager->persist($place);
            $entityManager->persist($sparring);
            $entityManager->flush();

            $link = $this->generateUrl('view_sparring_show', ['id' => $sparring->getId()]);

            return $this->redirect($link);
        }

        return $this->render('sparring/create.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("", name="view_sparring_list")
     */
    public function list(EntityManagerInterface $entityManager)
    {
        $sparrings = $entityManager
            ->getRepository(Sparring::class)
            ->findAll();

        return $this->render('sparring/list.twig',
            [
                'sparrings' => $sparrings
            ]);
    }

    /**
     * @Route("/{id}", requirements={"id": "\d+"}, name="view_sparring_show")
     */
    public function show(Sparring $sparring, Request $request,
                         EntityManagerInterface $entityManager, NormalizerInterface $normalizer)
    {
        $signups = $entityManager->getRepository(SignupSparring::class)
            ->findBy(['sparring' => $sparring]);

        if ($user = $this->getUser()) {
            $userSignups = array_filter($signups,
                fn(SignupSparring $signup) => $signup->user == $user);

            $signupSparring = $user ? new SignupSparring($user, $sparring) : null;

            $form = $this->createForm(SignupSparringType::class, $signupSparring);

            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager->persist($form->getData());
                $entityManager->flush();

                return $this->redirectToRoute("view_sparring_show", ['id' => $sparring->getId()]);
            }
        }

        return $this->render('sparring/show.twig',
            [
                'form' => isset($form) ? $form->createView() : null,
                'userSignup' => isset($userSignups) ? reset($userSignups) : null,
                'sparring' => $sparring,
                'signups' => $normalizer->normalize($signups)
            ]
        );
    }

    /**
     * @Route("/{id}/walki", requirements={"id": "\d+"}, name="view_sparring_fights")
     */
    public function fights(Sparring $sparring, Request $request,
                         EntityManagerInterface $entityManager, NormalizerInterface $normalizer)
    {
        $fightPropositions = $entityManager->getRepository(SparringProposition::class)
            ->findBy(['sparring' => $sparring]);

        if ($user = $this->getUser()) {
//            $userSignups = array_filter($signups,
//                fn(SignupSparring $signup) => $signup->user == $user);
//
//            $signupSparring = $user ? new SignupSparring($user, $sparring) : null;

            $form = $this->createForm(SparringPropositionType::class);

            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $sparringProposition = $form->getData();
                $sparringProposition->user = $user;
                $entityManager->persist($sparringProposition);
                $entityManager->flush();

                return $this->redirectToRoute("view_sparring_show", ['id' => $sparring->getId()]);
            }
        }

        return $this->render('sparring/fights.twig',
            [
                'form' => isset($form) ? $form->createView() : null,
                'userSignup' => isset($userSignups) ? reset($userSignups) : null,
                'sparring' => $sparring,
                'fightPropositions' => $fightPropositions
            ]
        );
    }
}
