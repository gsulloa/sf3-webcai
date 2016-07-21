<?php

namespace Cai\ComunicacionesBundle\Controller;

use Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Cai\ComunicacionesBundle\Entity\Solicitud;
use Cai\ComunicacionesBundle\Form\SolicitudType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * Solicitud controller.
 *
 */
class SolicitudController extends Controller
{
    const MAIL_COMUNICACIONES = "comunicaciones@cai.cl";
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
        $public = $this->get('cai_web.auxiliar')->getPublicInfo();
        return $this->render('CaiComunicacionesBundle:solicitud:show.html.twig', array(
            'public'     => $public,
            'solicitud' => $solicitud,
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
        ));
    }

    public function aceptarAction(Solicitud $solicitud){
        if(!$this->get('security.authorization_checker')->isGranted('ROLE_COMUNICACIONES')){
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        $solicitud->aceptar();
        $this->getDoctrine()->getManager()->persist($solicitud);
        $this->getDoctrine()->getManager()->flush();


        $session = new Session();
        $id = $solicitud->getId();
        $session->getFlashBag()->add("success","Solicitud $id aceptada");

        $this->aceptarMail($solicitud);
        return $this->redirectToRoute('solicitud_index');
    }

    public function rechazarAction(Request $request, Solicitud $solicitud){
        if(!$this->get('security.authorization_checker')->isGranted('ROLE_COMUNICACIONES')){
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        $solicitud->rechazar()
            ->setMensaje($request->request->get('mensaje'))
        ;
        $this->getDoctrine()->getManager()->persist($solicitud);
        $this->getDoctrine()->getManager()->flush();

        $session = new Session();
        $id = $solicitud->getId();
        $session->getFlashBag()->add("error","Solicitud $id rechazada");

        $this->rechazarMail($solicitud);
        return $this->redirectToRoute('solicitud_index');
    }

    public function completarAction(Solicitud $solicitud){
        if(!$this->get('security.authorization_checker')->isGranted('ROLE_COMUNICACIONES')){
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }


        $solicitud->completar();
        $this->getDoctrine()->getManager()->persist($solicitud);
        $this->getDoctrine()->getManager()->flush();

        $session = new Session();
        $id = $solicitud->getId();
        $session->getFlashBag()->add("success","Solicitud $id completada");

        $this->completarMail($solicitud);
        return $this->redirectToRoute('solicitud_index');
    }
    private function completarMail(Solicitud $solicitud){
        $params = array(
            "subject"       => "[CAi/Comunicaciones] Solicitud Completada",
            "to"            => $this->getUser()->getProfile()->getMail(),
            "type"          => "solicitud_completar_user",
            "renderParams"  => array(
                "user"  => $this->getUser()
            )
        );
        $mailing = $this->get('mailing');
        $mailing->send($params);

        $params = array(
            "subject"       => "[CAi/Comunicaciones] Solicitud Completada",
            "to"            => self::MAIL_COMUNICACIONES,
            "type"          => "solicitud_completar_com",
            "renderParams"  => array(
                "id_solicitud"  => $solicitud->getId()
            )
        );
        $mailing->send($params);
    }

    private function aceptarMail(Solicitud $solicitud){
        $params = array(
            "subject"       => "[CAi/Comunicaciones] Solicitud aceptada",
            "to"            => $this->getUser()->getProfile()->getMail(),
            "type"          => "solicitud_aceptar_user",
            "renderParams"  => array(
                "user"  => $this->getUser()
            )
        );
        $mailing = $this->get('mailing');
        $mailing->send($params);

        $params = array(
            "subject"       => "[CAi/Comunicaciones] Solicitud Aceptada",
            "to"            => self::MAIL_COMUNICACIONES,
            "type"          => "solicitud_aceptar_com",
            "renderParams"  => array(
                "id_solicitud"  => $solicitud->getId()
            )
        );
        $mailing->send($params);
    }

    private function rechazarMail(Solicitud $solicitud){
        $params = array(
            "subject"       => "[CAi/Comunicaciones] Solicitud Rechazada",
            "to"            => $this->getUser()->getProfile()->getMail(),
            "type"          => "solicitud_rechazar_user",
            "renderParams"  => array(
                "user"    => $this->getUser(),
                "mensaje" => $solicitud->getMensaje()
            )
        );
        $mailing = $this->get('mailing');
        $mailing->send($params);

        $params = array(
            "subject"       => "[CAi/Comunicaciones] Solicitud Rechazada",
            "to"            => self::MAIL_COMUNICACIONES,
            "type"          => "solicitud_rechazar_com",
            "renderParams"  => array(
                "id_solicitud"  => $solicitud->getId(),
                "mensaje"       => $solicitud->getMensaje()
            )
        );
        $mailing->send($params);
    }
    
}
