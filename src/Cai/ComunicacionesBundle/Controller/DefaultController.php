<?php

namespace Cai\ComunicacionesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('CaiComunicacionesBundle:Default:index.html.twig');
    }
}
