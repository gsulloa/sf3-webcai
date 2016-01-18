<?php

namespace Cai\FrontendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $contacto = $em->getRepository('CaiWebBundle:Contacto')->find(1);
        return $this->render('CaiFrontendBundle:Default:index.html.twig',array(
            'contacto'  => $contacto
        ));
    }
}
