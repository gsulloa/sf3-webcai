<?php

namespace Cai\FrontendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
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
        $categorias = $em->getRepository('CaiWebBundle:Categoria')->findAll();
        $auspicios_1 = $em->getRepository('CaiWebBundle:Slider')->findOneByTitulo('Auspicios_1');
        $auspicios_2 = $em->getRepository('CaiWebBundle:Slider')->findOneByTitulo('Auspicios_2');
        $principal = $em->getRepository('CaiWebBundle:Slider')->findOneByTitulo('Principal');
        $noticias = $em->getRepository('CaiWebBundle:Entrada')->findAll();
        return $this->render('CaiFrontendBundle:Default:index.html.twig',array(
            'contacto'  => $contacto,
            'categorias' => $categorias,
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
        $categorias = $em->getRepository('CaiWebBundle:Categoria')->findAll();
        $auspicios_1 = $em->getRepository('CaiWebBundle:Slider')->findOneByTitulo('Auspicios_1');
        $auspicios_2 = $em->getRepository('CaiWebBundle:Slider')->findOneByTitulo('Auspicios_2');

        return $this->render('CaiFrontendBundle:Default:entrada.html.twig', array(
            'entrada'       => $entrada,
            'categorias'    => $categorias,
            'contacto'  => $contacto,
            'auspicios_1' => $auspicios_1,
            'auspicios_2' => $auspicios_2,
        ));
    }

    public function noticiasAction($categoria){

        $em = $this->getDoctrine()->getManager();
        $contacto = $em->getRepository('CaiWebBundle:Contacto')->find(1);
        $categorias = $em->getRepository('CaiWebBundle:Categoria')->findAll();
        $auspicios_1 = $em->getRepository('CaiWebBundle:Slider')->findOneByTitulo('Auspicios_1');
        $auspicios_2 = $em->getRepository('CaiWebBundle:Slider')->findOneByTitulo('Auspicios_2');
        if($categoria == null){
            $entradas = $em->getRepository('CaiWebBundle:Entrada')->findAll();
        }else{
            $categoria = $em->getRepository('CaiWebBundle:Categoria')->findOneBySlug($categoria);
            $entradas = $em->createQuery(
                'SELECT e
                FROM CaiWebBundle:Entrada e
                LEFT JOIN e.categorias categorias
                WHERE categorias.id = ' . $categoria->getId()
            )->getResult();
            //$entradas = $em->getRepository('CaiWebBundle:Entrada')->findByCategorias($categoria);
        }
        return $this->render('CaiFrontendBundle:Default:noticias.html.twig', array(
            'noticias'       => $entradas,
            'categorias' => $categorias,
            'contacto'  => $contacto,
            'auspicios_1' => $auspicios_1,
            'auspicios_2' => $auspicios_2,
        ));
    }
    public function buscarAction(Request $request){
        $texto = $request->query->get('texto');
        $em = $this->getDoctrine()->getManager();
        $contacto = $em->getRepository('CaiWebBundle:Contacto')->find(1);
        $categorias = $em->getRepository('CaiWebBundle:Categoria')->findAll();
        $auspicios_1 = $em->getRepository('CaiWebBundle:Slider')->findOneByTitulo('Auspicios_1');
        $auspicios_2 = $em->getRepository('CaiWebBundle:Slider')->findOneByTitulo('Auspicios_2');

        $entradas = $em->createQuery(
            "SELECT e
            FROM CaiWebBundle:Entrada e
            WHERE e.titulo LIKE :texto
            OR e.cuerpo LIKE :texto
          "
            )->setParameter('texto','%'.$texto.'%')
            ->getResult()
        ;

        return $this->render('CaiFrontendBundle:Default:noticias.html.twig', array(
            'noticias'       => $entradas,
            'categorias' => $categorias,
            'contacto'  => $contacto,
            'auspicios_1' => $auspicios_1,
            'auspicios_2' => $auspicios_2,
        ));
    }
}
