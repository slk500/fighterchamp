<?php

namespace AppBundle\Controller\Api;

use AppBundle\Entity\User;
use AppBundle\Entity\UserCoach;
use AppBundle\Event\Events;
use AppBundle\Event\UserCreatedEvent;
use AppBundle\Form\User\CoachType;
use AppBundle\Form\User\FighterType;
use AppBundle\Form\User\UserType;
use AppBundle\Security\LoginFormAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;

/**
 * @Route("/api"))
 */
class UserController extends Controller
{
    public function show(User $user)
    {
        return $user;
    }

    /**
     * @Route("/users", name="api_user_create")
     * @Method("POST")
     */
    public function createAction(
        Request $request,
        EntityManagerInterface $em,
        EventDispatcherInterface $eventDispatcher,
        LoginFormAuthenticator $loginFormAuthenticator,
        GuardAuthenticatorHandler $guardAuthenticatorHandler
    ) {
        $form = $this->createForm($this->getFormType($request), null, [
            'method' => 'POST',
            'action' => $this->generateUrl('api_user_create')
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            /**
             * @var $user User
             */
            $user = $form->getData();
            $coachId = $form->get('coachId')->getData();

            if ($coachId) {
                $coach = $em->getRepository(User::class)
                    ->find($coachId);
                $userCoach = new UserCoach($user, $coach);
                $em->persist($userCoach);
            }

            $user->setHash(hash('sha256', md5(rand())));

            $em->persist($user);
            $em->flush();

            $eventDispatcher->dispatch(
                new UserCreatedEvent($user)
            );

            $this->addFlash('success', 'Sukces! Twój profil został utworzony! Jesteś zalogowany!');
            $this->addFlash(
                'danger',
                "Na twój email {$user->getEmail()} został wysłany link który musisz kliknąć aby twoje konto było aktywne"
            );

            $guardAuthenticatorHandler
                ->authenticateUserAndHandleSuccess(
                    $user,
                    $request,
                    $loginFormAuthenticator,
                    'main'
                );

            return new JsonResponse(
                ['location' => $this->generateUrl('view_user_show', ['id' => $user->getId()])],
                200
            );
        }

        return new JsonResponse(
            [
                'form' => $this->renderView(
                    $this->getFormTypeView($request),
                    [
                        'form' => $form->createView(),
                    ]
                )],
            400
        );
    }


    /**
     * @Route("/users", name="api_user_update")
     * @Method("PATCH")
     */
    public function updateAction(Request $request, EntityManagerInterface $em)
    {
        $user = $this->getUser();

        $form = $this->createForm($this->getFormType($request), $user, [
            'method' => 'PATCH',
            'action' => $this->generateUrl('api_user_create')
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            /**
             * @var $user User
             */
            $user = $form->getData();

            if ($form->has('coachId')) {
                $coachId = $form->get('coachId')->getData();
                if ($coachId) {
                    $coach = $em->getRepository(User::class)
                        ->find($coachId);
                    $userCoach = new UserCoach($user, $coach);
                    $em->persist($userCoach);
                }
            }

            if ($coach = $user->getCoaches()->first()) {
                $userCoachOld = $em->getRepository(UserCoach::class)
                    ->findOneBy(['coach' => $coach, 'fighter' => $user]);
                $em->remove($userCoachOld);
            }

            $em->flush();

            $this->addFlash('success', 'Sukces! Zmiany na twoim profilu zostały zapisane!!');

            return new JsonResponse(null, 200);
        }


        return new JsonResponse(
            [
                'form' => $this->renderView(
                    $this->getFormTypeView($request),
                    [
                        'form' => $form->createView(),
                    ]
                )],
            400
        );
    }

    /**
     * @Route("/users", name="api_user_list")
     * @Method("GET")
     */
    public function listAction(Request $request, EntityManagerInterface $em)
    {
        $type = $request->query->get('type', 1);

        if ($type == 1) {
            return $em->getRepository(User::class)->findAllFighters();
        }

        return  $em->getRepository(User::class)->findAllListAction($type);
    }

    /**
     * @Route("/users/{id}", name="api_user_delete")
     * @Method("DELETE")
     */
    public function delete(User $user)
    {
    }


    private function getFormType(Request $request): string
    {
        $data = $request->request->all();
        $type = $data['fighter']['type'] ?? $data['coach']['type'] ?? $data['user']['type'];

        switch ($type) {
            case '1':
                return FighterType::class;
            case '2':
                return CoachType::class;
            case '3':
                return UserType::class;
            default:
                return 'Type dosent exist';
        }
    }

    private function getFormTypeView(Request $request): string
    {
        $data = $request->request->all();
        $type = $data['fighter']['type'] ?? $data['coach']['type'] ?? $data['user']['type'];

        switch ($type) {
            case '1':
            case '2':
                return 'user/fighter/_form.html.twig';
            case '3':
                return 'user/fan/_form.html.twig';
            default:
                return 'Type dosent exist';
        }
    }
}
