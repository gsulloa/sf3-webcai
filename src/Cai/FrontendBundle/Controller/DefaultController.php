<?php

namespace Cai\FrontendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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

    public function homeAction()
    {
        $em = $this->getDoctrine()->getManager();
        $contacto = $em->getRepository('CaiWebBundle:Contacto')->find(1);
        $auspicios_1 = $em->getRepository('CaiWebBundle:Slider')->findOneByTitulo('Auspicios_1');
        $auspicios_2 = $em->getRepository('CaiWebBundle:Slider')->findOneByTitulo('Auspicios_2');
        $principal = $em->getRepository('CaiWebBundle:Slider')->findOneByTitulo('Principal');
        $noticias = $em->getRepository('CaiWebBundle:Entrada')->findAll();
        return $this->render('CaiFrontendBundle:Default:index.html.twig',array(
            'contacto'  => $contacto,
            'auspicios_1' => $auspicios_1,
            'auspicios_2' => $auspicios_2,
            'principal'   => $principal,
            'noticias'    => $noticias
        ));
    }

    public function entradaAction($slug){
        $em = $this->getDoctrine()->getManager();
        $entrada = $em->getRepository('CaiWebBundle:Entrada')->findOneBySlug($slug);
        if(!$entrada){
            throw $this->createNotFoundException();
        }
        $contacto = $em->getRepository('CaiWebBundle:Contacto')->find(1);
        $auspicios_1 = $em->getRepository('CaiWebBundle:Slider')->findOneByTitulo('Auspicios_1');
        $auspicios_2 = $em->getRepository('CaiWebBundle:Slider')->findOneByTitulo('Auspicios_2');

        return $this->render('CaiFrontendBundle:Default:entrada.html.twig', array(
            'entrada'       => $entrada,
            'contacto'  => $contacto,
            'auspicios_1' => $auspicios_1,
            'auspicios_2' => $auspicios_2,
        ));
    }

    public function noticiasAction(){
        $em = $this->getDoctrine()->getManager();
        $contacto = $em->getRepository('CaiWebBundle:Contacto')->find(1);
        $auspicios_1 = $em->getRepository('CaiWebBundle:Slider')->findOneByTitulo('Auspicios_1');
        $auspicios_2 = $em->getRepository('CaiWebBundle:Slider')->findOneByTitulo('Auspicios_2');
        $entradas = $em->getRepository('CaiWebBundle:Entrada')->findAll();
        return $this->render('CaiFrontendBundle:Default:noticias.html.twig', array(
            'noticias'       => $entradas,
            'contacto'  => $contacto,
            'auspicios_1' => $auspicios_1,
            'auspicios_2' => $auspicios_2,
        ));
    }
}
