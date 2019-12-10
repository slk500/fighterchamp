<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class JustRenderViewController extends Controller
{
    /**
     * @Route("/kontakt", name="view_contact")
     */
    public function contactController(Request $request)
    {
        return $this->render('contact/contact.html.twig');
    }

    /**
     * @Route("/regulamin", name="rules")
     */
    public function rulesController()
    {
        return $this->render('rules/rules.html.twig');
    }

    /**
     * @Route("/wesprzyj-projekt", name="support_project")
     */
    public function supportController()
    {
        return $this->render('wesprzyj-projekt/index.html.twig');
    }

    /**
     * @Route("/o-serwisie", name="view_about")
     */
    public function aboutController()
    {
        return $this->render('about/index.html.twig');
    }
}
