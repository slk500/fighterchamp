<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Form\Security\LoginForm;
use AppBundle\Form\Security\PasswordResetType;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class SecurityController extends Controller
{

    /**
     * @Route("/walidacja", name="user_validation")
     */
    public function validateAction(EntityManagerInterface $em, Request $request)
    {
        $hash = $request->query->get('hash');
        $email = $request->query->get('email');

        /**
         * @var $user User
         */
        $user = $em->getRepository(User::class)
            ->findOneBy(
                ['email' => $email,
                    'hash' => $hash]
            );

        if ($user) {
            $user->validate();
            $em->flush();
            $this->addFlash('success_info', 'Sukces. Twoje konto jest już aktywne.');
        } else {
            $this->addFlash('danger_info', 'Niepoprawne dane. Użyj linka który został wysłany na twojego maila.');
        }

        return $this->render('security/validate.html.twig');
    }


    /**
     * @Route("/login", name="view_login")
     */
    public function loginAction()
    {
        $authenticationUtils = $this->get('security.authentication_utils');
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();
        $form = $this->createForm(LoginForm::class, [
            '_username' => $lastUsername
        ]);

        return $this->render(
            'security/login.html.twig',
            [
                'form' => $form->createView(),
                'error' => $error,
            ]
        );
    }


    /**
     * @Route("/reset", name="passwordReset")
     */
    public function passwordReset(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        $form = $this->createForm(PasswordResetType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $formData = $form->getData();
            $userEmail = $formData['_username'];

            $em = $this->getDoctrine()->getManager();
            $user = $em->getRepository('AppBundle:User')
                ->findOneBy(['email' => $userEmail]);

            if ($user) {
                $new_password = time();

                $encoded_password = $passwordEncoder->encodePassword($user, $new_password);

                $user->setPassword($encoded_password);
                $em->flush();

                $transport = (new \Swift_SmtpTransport('smtp.zenbox.pl', 587))
                    ->setUsername('fighterchamp@fighterchamp.pl')
                    ->setPassword(
                        $this->container->getParameter('mailer_password')
                    );

                $mailer = new \Swift_Mailer($transport);

                $message = (new \Swift_Message())
                    ->setSubject('Password Reset')
                    ->setFrom('fighterchamp@fighterchamp.pl', 'FighterChamp')
                    ->setTo($userEmail)
                    ->setBody("Nowe Hasło: " . $new_password, 'text/html');

                $numberOfSuccessfulSent = $mailer->send($message);

                $this->addFlash('success_info', 'Sukces. Twoje nowe hasło zostało wysłane na ' . $userEmail);
            } else {
                $this->addFlash('danger_info', 'Użytkownik o podanej nazwie nie istnieje.');
            }

            return $this->redirectToRoute('login');
        }

        return $this->render('security/password_reset.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/logout", name="logout")
     */
    public function logoutAction()
    {
    }


    /**
     * @Route("/setnullonimage", name="setNullOnImage", options={"expose"=true})
     */
    public function setNullOnImageFile()
    {
        $session = new Session();
        $session->set('imageName', null);

        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            $em = $this->getDoctrine()->getManager();
            $this->getUser()->removeFile();
            $em->flush();
        }

        return new Response(200);
    }
}
