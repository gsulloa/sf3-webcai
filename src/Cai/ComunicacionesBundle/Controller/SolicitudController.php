<?php

namespace Cai\ComunicacionesBundle\Controller;

use Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Cai\ComunicacionesBundle\Entity\Solicitud;
use Cai\ComunicacionesBundle\Form\SolicitudType;

/**
 * Solicitud controller.
 *
 */
class SolicitudController extends Controller
{
    /**
     * Lists all Solicitud entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $solicituds = $em->getRepository('CaiComunicacionesBundle:Solicitud')->findByUserOrderedByFecha($this->getUser()->getId());

        $all = [];
        if($this->get('security.authorization_checker')->isGranted('ROLE_COMUNICACIONES')){
            $all = $em->getRepository('CaiComunicacionesBundle:Solicitud')->findAllOrderedByFecha();
        }
        $public = $this->get('cai_web.auxiliar')->getPublicInfo();
        return $this->render('CaiComunicacionesBundle:solicitud:index.html.twig', array(
            'public'     => $public,
            'solicituds' => $solicituds,
            'all'        => $all
        ));
    }

    /**
     * Creates a new Solicitud entity.
     *
     */
    public function newAction(Request $request)
    {
        $solicitud = new Solicitud();
        $form = $this->createForm('Cai\ComunicacionesBundle\Form\SolicitudType', $solicitud);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $solicitud->setUser($this->getUser());
            $em = $this->getDoctrine()->getManager();
            $em->persist($solicitud);
            $em->flush();

            return $this->redirectToRoute('solicitud_show', array('id' => $solicitud->getId()));
        }
        $public = $this->get('cai_web.auxiliar')->getPublicInfo();
        return $this->render('CaiComunicacionesBundle:solicitud:new.html.twig', array(
            'public'     => $public,
            'solicitud' => $solicitud,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Solicitud entity.
     *
     */
    public function showAction(Solicitud $solicitud)
    {
        if(($solicitud->getUser() != $this->getUser() &&
            !$this->get('security.authorization_checker')->isGranted('ROLE_COMUNICACIONES'))
        ){
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        $deleteForm = $this->createDeleteForm($solicitud);
        $public = $this->get('cai_web.auxiliar')->getPublicInfo();
        return $this->render('CaiComunicacionesBundle:solicitud:show.html.twig', array(
            'public'     => $public,
            'solicitud' => $solicitud,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Solicitud entity.
     *
     */
    public function editAction(Request $request, Solicitud $solicitud)
    {
        if(($solicitud->getUser() != $this->getUser() &&
            !$this->get('security.authorization_checker')->isGranted('ROLE_COMUNICACIONES')) ||
            $solicitud->getEstado() > 0
        ){
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        $deleteForm = $this->createDeleteForm($solicitud);
        $editForm = $this->createForm('Cai\ComunicacionesBundle\Form\SolicitudType', $solicitud);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($solicitud);
            $em->flush();

            return $this->redirectToRoute('solicitud_edit', array('id' => $solicitud->getId()));
        }
        $public = $this->get('cai_web.auxiliar')->getPublicInfo();
        return $this->render('CaiComunicacionesBundle:solicitud:edit.html.twig', array(
            'public'     => $public,
            'solicitud' => $solicitud,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    public function aceptarAction(Solicitud $solicitud){
        if(!$this->get('security.authorization_checker')->isGranted('ROLE_COMUNICACIONES')){
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        $solicitud->aceptar();
        $this->getDoctrine()->getManager()->persist($solicitud);
        $this->getDoctrine()->getManager()->flush();
        
        return $this->redirectToRoute('solicitud_index');
    }

    public function rechazarAction(Solicitud $solicitud){
        if(!$this->get('security.authorization_checker')->isGranted('ROLE_COMUNICACIONES')){
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        $solicitud->rechazar();
        $this->getDoctrine()->getManager()->persist($solicitud);
        $this->getDoctrine()->getManager()->flush();
        return $this->redirectToRoute('solicitud_index');
    }

    public function completarAction(Solicitud $solicitud){
        if(!$this->get('security.authorization_checker')->isGranted('ROLE_COMUNICACIONES')){
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        $solicitud->completar();
        $this->getDoctrine()->getManager()->persist($solicitud);
        $this->getDoctrine()->getManager()->flush();
        return $this->redirectToRoute('solicitud_index');
    }
    
}
