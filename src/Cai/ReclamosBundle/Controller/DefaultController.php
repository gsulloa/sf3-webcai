<?php

namespace Cai\ReclamosBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('CaiReclamosBundle:Default:index.html.twig');
    }
}
