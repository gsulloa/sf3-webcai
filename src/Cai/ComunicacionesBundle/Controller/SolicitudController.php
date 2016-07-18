<?php

namespace Cai\ComunicacionesBundle\Controller;

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

        $solicituds = $em->getRepository('CaiComunicacionesBundle:Solicitud')->findAll();
        $public = $this->get('cai_web.auxiliar')->getPublicInfo();
        return $this->render('CaiComunicacionesBundle:solicitud:index.html.twig', array(
            'public'     => $public,
            'solicituds' => $solicituds,
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

    /**
     * Deletes a Solicitud entity.
     *
     */
    public function deleteAction(Request $request, Solicitud $solicitud)
    {
        $form = $this->createDeleteForm($solicitud);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($solicitud);
            $em->flush();
        }

        return $this->redirectToRoute('solicitud_index');
    }

    /**
     * Creates a form to delete a Solicitud entity.
     *
     * @param Solicitud $solicitud The Solicitud entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Solicitud $solicitud)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('solicitud_delete', array('id' => $solicitud->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
