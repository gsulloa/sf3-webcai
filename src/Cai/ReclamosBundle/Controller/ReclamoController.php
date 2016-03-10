<?php

namespace Cai\ReclamosBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Cai\ReclamosBundle\Entity\Reclamo;
use Cai\ReclamosBundle\Form\ReclamoType;

/**
 * Reclamo controller.
 *
 */
class ReclamoController extends Controller
{
    /**
     * Lists all Reclamo entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $reclamos = $em->getRepository('CaiReclamosBundle:Reclamo')->findAll();

        return $this->render('CaiReclamosBundle:reclamo:index.html.twig', array(
            'reclamos' => $reclamos,
        ));
    }

    /**
     * Creates a new Reclamo entity.
     *
     */
    public function newAction(Request $request)
    {
        $reclamo = new Reclamo();
        $form = $this->createForm('Cai\ReclamosBundle\Form\ReclamoType', $reclamo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($reclamo);
            $em->flush();

            return $this->redirectToRoute('reclamo_show', array('id' => $reclamo->getId()));
        }

        return $this->render('CaiReclamosBundle:reclamo:new.html.twig', array(
            'reclamo' => $reclamo,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Reclamo entity.
     *
     */
    public function showAction(Reclamo $reclamo)
    {
        $deleteForm = $this->createDeleteForm($reclamo);

        return $this->render('CaiReclamosBundle:reclamo:show.html.twig', array(
            'reclamo' => $reclamo,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Reclamo entity.
     *
     */
    public function editAction(Request $request, Reclamo $reclamo)
    {
        $deleteForm = $this->createDeleteForm($reclamo);
        $editForm = $this->createForm('Cai\ReclamosBundle\Form\ReclamoType', $reclamo);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($reclamo);
            $em->flush();

            return $this->redirectToRoute('reclamo_edit', array('id' => $reclamo->getId()));
        }

        return $this->render('CaiReclamosBundle:reclamo:edit.html.twig', array(
            'reclamo' => $reclamo,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Reclamo entity.
     *
     */
    public function deleteAction(Request $request, Reclamo $reclamo)
    {
        $form = $this->createDeleteForm($reclamo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($reclamo);
            $em->flush();
        }

        return $this->redirectToRoute('reclamo_index');
    }

    /**
     * Creates a form to delete a Reclamo entity.
     *
     * @param Reclamo $reclamo The Reclamo entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Reclamo $reclamo)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('reclamo_delete', array('id' => $reclamo->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
