<?php

namespace AppBundle\Controller\Api;

use AppBundle\Entity\User;
use AppBundle\Form\User\CoachType;
use AppBundle\Form\User\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Form\User\FighterType;
use Symfony\Component\Serializer\Serializer;


// condition="request.isXmlHttpRequest()

/**
 * @Route("/api")
 */
class UserController extends Controller
{
    /**
     * @Route("/ludzie/{id}", name="api_user_show")
     * @Method("GET")
     */
    public function showAction(User $user)
    {
        $user = $this->get('serializer.my')->serialize($user, 'json');

        return new Response($user, 200, ['Content-Type' => 'application/json']);
    }


    /**
     * @Route("/user-create", name="user_create")
     * @Method("POST")
     */
    public function createAction(Request $request, EntityManagerInterface $em)
    {

        $form = $this->createForm($this->getFormType($request), null, [
            'method' => 'POST',
            'action' => $this->generateUrl('user_create'),
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            /**
             * @var $user User
             */
            $user = $form->getData();

            if($coach = $user->getCoach()){
                if(!$user->getType() === 1) $user->removeUser($coach);
            }

            $em->persist($user);
            $em->flush();

            $this->addFlash('success', 'Sukces! Twój profil został utworzony! Jesteś zalogowany!');

            $this->get('security.authentication.guard_handler')
                ->authenticateUserAndHandleSuccess(
                    $user,
                    $request,
                    $this->get('app.security.login_form_authenticator'),
                    'main'
                );

            return new JsonResponse(
                ['location' => $this->generateUrl('user_show', ['id' => $user->getId()])], 200);
        }

        return new JsonResponse(
            [
                'form' => $this->renderView($this->getFormTypeView($request),
                    [
                        'form' => $form->createView(),
                    ])], 400);
    }


    /**
     * @Route("/user-update", name="api_user_update")
     */
    public function updateAction(Request $request, EntityManagerInterface $em)
    {
        $user = $this->getUser();

        $form = $this->createForm($this->getFormType($request), $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $formUser = $form->getData();

            $em->flush();

            $this->addFlash('success', 'Sukces! Zmiany na twoim profilu zostały zapisane!!');

            return new JsonResponse(200);
        }

        return new JsonResponse(
            [
                'form' => $this->renderView($this->getFormTypeView($request),
                    [
                        'form' => $form->createView(),
                    ])], 400);
    }

    /**
     * @Route("/ludzie", name="api_user_list")
     * @Method("GET")
     */
    public function listAction(EntityManagerInterface $em, Request $request)
    {
        $queryType = $request->query->get('type');

        $type = null;
        if(($queryType === '1' || $queryType === '2' || $queryType === '3')){
            $type = (int)$queryType;
        }



        $users = $em->getRepository(User::class)->findAllByType($type);

        return new JsonResponse(['data' => $users]);
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
                return 'Nie ma takiego typu';
        }
    }

    private function getFormTypeView(Request $request): string
    {
        $data = $request->request->all();
        $type = $data['fighter']['type'] ?? $data['coach']['type'] ?? $data['user']['type'];

        switch ($type) {
            case '1':
                return 'user/fighter/_form.html.twig';
            case '2':
                return 'user/coach/_form.html.twig';
            case '3':
                return 'user/fan/_form.html.twig';
            default:
                return 'Nie ma takiego typu';
        }
    }



}