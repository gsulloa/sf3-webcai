<?php

namespace Cai\WebBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class TestController extends Controller
{
    public function mailAction($text){
        $aux = $this->get('cai_web.auxiliar');
        var_dump($aux->toAscii($text));
        exit;
        return $this->renderView('CaiWebBundle:Default:index.html.twig');
    }

}
