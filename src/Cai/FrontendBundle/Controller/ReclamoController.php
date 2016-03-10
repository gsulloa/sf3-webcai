<?php

namespace Cai\FrontendBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Cai\ReclamosBundle\Entity\Reclamo;
use Cai\ReclamosBundle\Form\ReclamoType;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * Reclamo controller.
 *
 */
class ReclamoController extends Controller
{
    /**
     * Creates a new Reclamo entity.
     *
     */
    public function newAction(Request $request)
    {
        $reclamo = new Reclamo();
        $form = $this->createForm('Cai\ReclamosBundle\Form\ReclamoType', $reclamo);
        $form->handleRequest($request);
        $em = $this->getDoctrine()->getManager();
        $contacto = $em->getRepository('CaiWebBundle:Contacto')->find(1);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($reclamo);
            $em->flush();
            $session = new Session();
            $session->getFlashbag()->add('success',"El reclamo ha sido ingresado con éxito. Este será revisado y te contactaremos. Para más consultas no dudes en escribirnos a ". $contacto->getMail() . ".");
            unset($reclamo);
            unset($form);
            $reclamo = new Reclamo();
            $form = $this->createForm('Cai\ReclamosBundle\Form\ReclamoType', $reclamo);
        }
        $categorias = $em->getRepository('CaiWebBundle:Categoria')->findAll();
        $auspicios_1 = $em->getRepository('CaiWebBundle:Slider')->findOneByTitulo('Auspicios_1');
        $auspicios_2 = $em->getRepository('CaiWebBundle:Slider')->findOneByTitulo('Auspicios_2');
        $principal = $em->getRepository('CaiWebBundle:Slider')->findOneByTitulo('Principal');
        $menu = $em->getRepository('CaiWebBundle:Menu')->findOneByTitulo('Principal');

        return $this->render('CaiFrontendBundle:reclamo:new.html.twig', array(
            'reclamo' => $reclamo,
            'contacto'  => $contacto,
            'categorias' => $categorias,
            'auspicios_1' => $auspicios_1,
            'auspicios_2' => $auspicios_2,
            'principal'   => $principal,
            'menu'        => $menu,
            'form' => $form->createView(),
        ));
    }
}