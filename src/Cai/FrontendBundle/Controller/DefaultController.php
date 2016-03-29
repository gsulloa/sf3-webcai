<?php

namespace Cai\FrontendBundle\Controller;

use Cai\WebBundle\Entity\Seguimiento;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->redirectToRoute('cai_frontend_home');
        $em = $this->getDoctrine()->getManager();
        $contacto = $em->getRepository('CaiWebBundle:Contacto')->find(1);
        return $this->render('CaiFrontendBundle:Default:mantenimiento.html.twig',array(
            'contacto'  => $contacto
        ));
    }

    public function homeAction()
    {
        $em = $this->getDoctrine()->getManager();
        $seguimiento = new Seguimiento();
        $seguimiento->setEtiqueta('inicio')
            ->setFecha(new \DateTime())
            ->setUser($this->getUser());
        $em->persist($seguimiento);
        $em->flush();
        $contacto = $em->getRepository('CaiWebBundle:Contacto')->find(1);
        $categorias = $em->getRepository('CaiWebBundle:Categoria')->findAll();
        $auspicios_1 = $em->getRepository('CaiWebBundle:Slider')->findOneByTitulo('Auspicios_1');
        $auspicios_2 = $em->getRepository('CaiWebBundle:Slider')->findOneByTitulo('Auspicios_2');
        $principal = $em->getRepository('CaiWebBundle:Slider')->findOneByTitulo('Principal');
        $menu = $em->getRepository('CaiWebBundle:Menu')->findOneByTitulo('Principal');
        if($this->isGranted('IS_AUTHENTICATED_FULLY')){
            $noticias = $em->getRepository('CaiWebBundle:Entrada')->findAllForUser($this->getUser());
        }
        else {
            $noticias = $em->getRepository('CaiWebBundle:Entrada')->findAllForHome();
        }
        return $this->render('CaiFrontendBundle:Default:index.html.twig',array(
            'contacto'  => $contacto,
            'categorias' => $categorias,
            'auspicios_1' => $auspicios_1,
            'auspicios_2' => $auspicios_2,
            'principal'   => $principal,
            'noticias'    => $noticias,
            'menu'        => $menu
        ));
    }

    public function entradaAction($slug){
        $em = $this->getDoctrine()->getManager();

        $entrada = $em->getRepository('CaiWebBundle:Entrada')->findOneBySlugFront($slug);
        if(!$entrada){
            throw $this->createNotFoundException();
        }
        $seguimiento = new Seguimiento();
        $seguimiento->setEtiqueta('entrada')
            ->setFecha(new \DateTime())
            ->setUser($this->getUser())
            ->setEtiquetaId($entrada->getId())
        ;
        $em->persist($seguimiento);
        $em->flush();
        $contacto = $em->getRepository('CaiWebBundle:Contacto')->find(1);
        $categorias = $em->getRepository('CaiWebBundle:Categoria')->findAll();
        $auspicios_1 = $em->getRepository('CaiWebBundle:Slider')->findOneByTitulo('Auspicios_1');
        $auspicios_2 = $em->getRepository('CaiWebBundle:Slider')->findOneByTitulo('Auspicios_2');
        $menu = $em->getRepository('CaiWebBundle:Menu')->findOneByTitulo('Principal');

        return $this->render('CaiFrontendBundle:Default:entrada.html.twig', array(
            'entrada'       => $entrada,
            'categorias'    => $categorias,
            'contacto'  => $contacto,
            'auspicios_1' => $auspicios_1,
            'auspicios_2' => $auspicios_2,
            'menu'        => $menu,
        ));
    }
    public function paginaAction($slug){
        $em = $this->getDoctrine()->getManager();

        $pagina = $em->getRepository('CaiWebBundle:Pagina')->findOneBySlug($slug);
        if(!$pagina){
            throw $this->createNotFoundException();
        }

        $seguimiento = new Seguimiento();
        $seguimiento->setEtiqueta('pagina')
            ->setFecha(new \DateTime())
            ->setUser($this->getUser())
            ->setEtiquetaId($pagina->getId())
        ;
        $em->persist($seguimiento);
        $em->flush();
        $contacto = $em->getRepository('CaiWebBundle:Contacto')->find(1);
        $categorias = $em->getRepository('CaiWebBundle:Categoria')->findAll();
        $auspicios_1 = $em->getRepository('CaiWebBundle:Slider')->findOneByTitulo('Auspicios_1');
        $auspicios_2 = $em->getRepository('CaiWebBundle:Slider')->findOneByTitulo('Auspicios_2');
        $menu = $em->getRepository('CaiWebBundle:Menu')->findOneByTitulo('Principal');
        $aux = $this->get('cai_web.auxiliar');
        $pagina->setCuerpo($aux->getShortcodesInfo($pagina->getCuerpo()));
        return $this->render('CaiFrontendBundle:Default:pagina.html.twig', array(
            'pagina'       => $pagina,
            'categorias'    => $categorias,
            'contacto'  => $contacto,
            'auspicios_1' => $auspicios_1,
            'auspicios_2' => $auspicios_2,
            'menu'        => $menu,
        ));
    }

    public function noticiasAction(Request $request, $categoria){

        $em = $this->getDoctrine()->getManager();
        $contacto = $em->getRepository('CaiWebBundle:Contacto')->find(1);
        $categorias = $em->getRepository('CaiWebBundle:Categoria')->findAll();
        $auspicios_1 = $em->getRepository('CaiWebBundle:Slider')->findOneByTitulo('Auspicios_1');
        $auspicios_2 = $em->getRepository('CaiWebBundle:Slider')->findOneByTitulo('Auspicios_2');
        $menu = $em->getRepository('CaiWebBundle:Menu')->findOneByTitulo('Principal');
        if($categoria == null){
            $seguimiento = new Seguimiento();
            $seguimiento->setEtiqueta('noticias')
                ->setFecha(new \DateTime())
                ->setUser($this->getUser());
            $em->persist($seguimiento);
            $em->flush();
            $entradas = $em->getRepository('CaiWebBundle:Entrada')->findAllOrdered();
        }else{
            $categoria = $em->getRepository('CaiWebBundle:Categoria')->findOneBySlug($categoria);
            $seguimiento = new Seguimiento();
            $seguimiento->setEtiqueta('noticias')
                ->setFecha(new \DateTime())
                ->setUser($this->getUser())
                ->setEtiquetaId($categoria->getId())
            ;
            $em->persist($seguimiento);
            $em->flush();

            $entradas = $em->getRepository('CaiWebBundle:Entrada')->findOrderedByCategoria($categoria);
        }
        $paginator  = $this->get('knp_paginator');
        $entradas = $paginator->paginate(
            $entradas,
            $request->query->getInt('page', 1)/*page number*/,
            10/*limit per page*/
        );
        return $this->render('CaiFrontendBundle:Default:noticias.html.twig', array(
            'noticias'       => $entradas,
            'categorias' => $categorias,
            'contacto'  => $contacto,
            'auspicios_1' => $auspicios_1,
            'auspicios_2' => $auspicios_2,
            'menu' => $menu,
        ));
    }
    public function buscarAction(Request $request){
        $texto = $request->query->get('texto');
        $em = $this->getDoctrine()->getManager();
        $seguimiento = new Seguimiento();
        $seguimiento->setEtiqueta('buscar')
            ->setFecha(new \DateTime())
            ->setUser($this->getUser())
            ->setText($texto)
        ;
        $em->persist($seguimiento);
        $em->flush();
        $contacto = $em->getRepository('CaiWebBundle:Contacto')->find(1);
        $categorias = $em->getRepository('CaiWebBundle:Categoria')->findAll();
        $auspicios_1 = $em->getRepository('CaiWebBundle:Slider')->findOneByTitulo('Auspicios_1');
        $auspicios_2 = $em->getRepository('CaiWebBundle:Slider')->findOneByTitulo('Auspicios_2');
        $entradas = $em->getRepository('CaiWebBundle:Entrada')->findBySearchedText($texto);
        $menu = $em->getRepository('CaiWebBundle:Menu')->findOneByTitulo('Principal');
        $paginator  = $this->get('knp_paginator');
        $entradas = $paginator->paginate(
            $entradas,
            $request->query->getInt('page', 1)/*page number*/,
            10/*limit per page*/
        );
        return $this->render('CaiFrontendBundle:Default:noticias.html.twig', array(
            'noticias'       => $entradas,
            'categorias' => $categorias,
            'contacto'  => $contacto,
            'auspicios_1' => $auspicios_1,
            'auspicios_2' => $auspicios_2,
            'menu'  => $menu
        ));
    }
}
