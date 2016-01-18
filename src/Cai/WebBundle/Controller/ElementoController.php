<?php

namespace Cai\WebBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Cai\WebBundle\Entity\Elemento;
use Cai\WebBundle\Form\ElementoType;

/**
 * Elemento controller.
 *
 */
class ElementoController extends Controller
{
    /**
     * Lists all Elemento entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $elementos = $em->getRepository('CaiWebBundle:Elemento')->findAll();

        return $this->render('CaiWebBundle:elemento:index.html.twig', array(
            'elementos' => $elementos,
        ));
    }

    /**
     * Creates a new Elemento entity.
     *
     */
    public function newAction(Request $request)
    {
        $elemento = new Elemento();
        $form = $this->createForm('Cai\WebBundle\Form\ElementoType', $elemento);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($elemento);
            $em->flush();

            return $this->redirectToRoute('elemento_show', array('id' => $elemento->getId()));
        }

        return $this->render('CaiWebBundle:elemento:new.html.twig', array(
            'elemento' => $elemento,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Elemento entity.
     *
     */
    public function showAction(Elemento $elemento)
    {
        $deleteForm = $this->createDeleteForm($elemento);

        return $this->render('CaiWebBundle:elemento:show.html.twig', array(
            'elemento' => $elemento,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Elemento entity.
     *
     */
    public function editAction(Request $request, Elemento $elemento)
    {
        $deleteForm = $this->createDeleteForm($elemento);
        $editForm = $this->createForm('Cai\WebBundle\Form\ElementoType', $elemento);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($elemento);
            $em->flush();

            return $this->redirectToRoute('elemento_edit', array('id' => $elemento->getId()));
        }

        return $this->render('CaiWebBundle:elemento:edit.html.twig', array(
            'elemento' => $elemento,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Elemento entity.
     *
     */
    public function deleteAction(Request $request, Elemento $elemento)
    {
        $form = $this->createDeleteForm($elemento);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($elemento);
            $em->flush();
        }

        return $this->redirectToRoute('elemento_index');
    }

    /**
     * Creates a form to delete a Elemento entity.
     *
     * @param Elemento $elemento The Elemento entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Elemento $elemento)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('elemento_delete', array('id' => $elemento->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
