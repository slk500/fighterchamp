<?php
/**
 * Created by PhpStorm.
 * User: slk500
 * Date: 18.01.17
 * Time: 00:17
 */

namespace AppBundle\Controller;


use AppBundle\Form\ContactType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class JustRenderViewController extends Controller
{
    /**
     * @Route("/kontakt", name="contact")
     */
    public function contactController(Request $request)
    {
        $form = $this->createForm(ContactType::class, null);

            $form->handleRequest($request);

            if($form->isValid()){

                $this->get('appmailer')
                    ->sendEmail($form['email']->getData(),'Kontakt','Chcę współpracować');

                    return $this->redirect($request->getUri());
                }


        return $this->render('contact/contact.html.twig',[
            'form' => $form->createView()
        ]);
    }


    /**
     * @Route("/regulamin", name="rules")
     */
    public function rulesController()
    {
        return $this->render('rules/rules.html.twig');
    }

}

