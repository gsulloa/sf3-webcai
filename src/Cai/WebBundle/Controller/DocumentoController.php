<?php

namespace Cai\WebBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Cai\WebBundle\Entity\Documento;
use Cai\WebBundle\Form\DocumentoType;

/**
 * Documento controller.
 *
 */
class DocumentoController extends Controller
{
    /**
     * Lists all Documento entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $documentos = $em->getRepository('CaiWebBundle:Documento')->findAll();
        return $this->render('CaiWebBundle:documento:index.html.twig', array(
            'documentos' => $documentos,
        ));
    }

    /**
     * Creates a new Documento entity.
     *
     */
    public function newAction(Request $request)
    {
        $documento = new Documento();
        $form = $this->createForm('Cai\WebBundle\Form\DocumentoType', $documento);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $aux = $this->get('cai_web.auxiliar');

            $em->persist($documento);
            $em->flush();

            return $this->redirectToRoute('documento_show', array('id' => $documento->getId()));
        }

        return $this->render('CaiWebBundle:documento:new.html.twig', array(
            'documento' => $documento,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Documento entity.
     *
     */
    public function showAction(Documento $documento)
    {
        $deleteForm = $this->createDeleteForm($documento);

        return $this->render('CaiWebBundle:documento:show.html.twig', array(
            'documento' => $documento,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Documento entity.
     *
     */
    public function editAction(Request $request, Documento $documento)
    {
        $deleteForm = $this->createDeleteForm($documento);
        $editForm = $this->createForm('Cai\WebBundle\Form\DocumentoType', $documento);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($documento);
            $em->flush();

            return $this->redirectToRoute('documento_edit', array('id' => $documento->getId()));
        }

        return $this->render('CaiWebBundle:documento:edit.html.twig', array(
            'documento' => $documento,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Documento entity.
     *
     */
    public function deleteAction(Request $request, Documento $documento)
    {
        $form = $this->createDeleteForm($documento);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($documento);
            $em->flush();
        }

        return $this->redirectToRoute('documento_index');
    }

    /**
     * Creates a form to delete a Documento entity.
     *
     * @param Documento $documento The Documento entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Documento $documento)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('documento_delete', array('id' => $documento->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
