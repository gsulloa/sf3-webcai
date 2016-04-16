<?php

namespace Cai\WebBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class TestController extends Controller
{
    public function mailAction(){
        return $this->render('CaiWebBundle:Default:index.html.twig');
    }

}
