<?php

namespace Cai\ClubesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('CaiClubesBundle:Default:index.html.twig');
    }
}
