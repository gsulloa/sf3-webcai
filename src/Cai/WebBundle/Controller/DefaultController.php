<?php

namespace Cai\WebBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('CaiWebBundle:Default:mantenimiento.html.twig');
    }
}
