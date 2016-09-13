<?php

namespace Cai\ColumnasBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('CaiColumnasBundle:Default:index.html.twig');
    }
}
