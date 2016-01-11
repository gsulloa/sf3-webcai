<?php

namespace Cai\WebBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Cai\WebBundle\Entity\Entrada;
use Cai\WebBundle\Form\EntradaType;

/**
 * Entrada controller.
 *
 */
class EntradaController extends Controller
{
    /**
     * Lists all Entrada entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entradas = $em->getRepository('CaiWebBundle:Entrada')->findAll();

        return $this->render('CaiWebBundle:Entrada:index.html.twig', array(
            'entradas' => $entradas,
        ));
    }

    /**
     * Creates a new Entrada entity.
     *
     */
    public function newAction(Request $request)
    {
        $entrada = new Entrada();
        $form = $this->createForm('Cai\WebBundle\Form\EntradaType', $entrada);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entrada);
            $em->flush();

            return $this->redirectToRoute('entrada_show', array('id' => $entrada->getId()));
        }

        return $this->render('CaiWebBundle:Entrada:new.html.twig', array(
            'entrada' => $entrada,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Entrada entity.
     *
     */
    public function showAction(Entrada $entrada)
    {
        $deleteForm = $this->createDeleteForm($entrada);

        return $this->render('CaiWebBundle:Entrada:show.html.twig', array(
            'entrada' => $entrada,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Entrada entity.
     *
     */
    public function editAction(Request $request, Entrada $entrada)
    {
        $deleteForm = $this->createDeleteForm($entrada);
        $editForm = $this->createForm('Cai\WebBundle\Form\EntradaType', $entrada);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entrada);
            $em->flush();

            return $this->redirectToRoute('entrada_edit', array('id' => $entrada->getId()));
        }

        return $this->render('CaiWebBundle:Entrada:edit.html.twig', array(
            'entrada' => $entrada,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Entrada entity.
     *
     */
    public function deleteAction(Request $request, Entrada $entrada)
    {
        $form = $this->createDeleteForm($entrada);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($entrada);
            $em->flush();
        }

        return $this->redirectToRoute('entrada_index');
    }

    /**
     * Creates a form to delete a Entrada entity.
     *
     * @param Entrada $entrada The Entrada entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Entrada $entrada)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('entrada_delete', array('id' => $entrada->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
