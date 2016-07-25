<?php

namespace Cai\ApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('CaiApiBundle:Default:index.html.twig');
    }
}
