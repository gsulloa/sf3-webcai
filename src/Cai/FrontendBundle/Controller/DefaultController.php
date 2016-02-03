<?php

namespace Cai\FrontendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $contacto = $em->getRepository('CaiWebBundle:Contacto')->find(1);
        return $this->render('CaiFrontendBundle:Default:mantenimiento.html.twig',array(
            'contacto'  => $contacto
        ));
    }

    public function propuestaAction($i)
    {
        $em = $this->getDoctrine()->getManager();
        $contacto = $em->getRepository('CaiWebBundle:Contacto')->find(1);
        $auspicios = $em->getRepository('CaiWebBundle:Slider')->findOneByTitulo('Auspicios');
        return $this->render('CaiFrontendBundle:Default:index'.$i.'.html.twig',array(
            'contacto'  => $contacto,
            'auspicios' => $auspicios
        ));
    }
}
