<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class JustRenderViewController extends Controller
{
    /**
     * @Route("/kontakt", name="view_contact")
     */
    public function contactController()
    {
        return $this->render('contact/contact.html.twig');
    }

    /**
     * @Route("/regulamin", name="view_rules")
     */
    public function rulesController()
    {
        return $this->render('rules/rules.html.twig');
    }

    /**
     * @Route("/wesprzyj-projekt", name="view_support_project")
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
