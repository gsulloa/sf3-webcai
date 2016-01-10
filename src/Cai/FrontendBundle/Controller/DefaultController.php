<?php

namespace Cai\FrontendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('CaiFrontendBundle:Default:index.html.twig');
    }
}
