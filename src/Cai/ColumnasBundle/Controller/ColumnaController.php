<?php

namespace Cai\ColumnasBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Cai\ColumnasBundle\Entity\Columna;
use Cai\ColumnasBundle\Form\ColumnaType;

/**
 * Columna controller.
 *
 */
class ColumnaController extends Controller
{
    /**
     * Lists all Columna entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $columnas = $em->getRepository('CaiColumnasBundle:Columna')->findAll();

        return $this->render('columna/index.html.twig', array(
            'columnas' => $columnas,
        ));
    }

    /**
     * Creates a new Columna entity.
     *
     */
    public function newAction(Request $request)
    {
        $columna = new Columna();
        $form = $this->createForm('Cai\ColumnasBundle\Form\ColumnaType', $columna);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            //generar Slug
            $columna->setSlugGenerator($this->get('slug_generator.columna'))
                ->generateSlug();
            $em->persist($columna);
            $em->flush();

            return $this->redirectToRoute('columna_show', array('id' => $columna->getId()));
        }

        return $this->render('columna/new.html.twig', array(
            'columna' => $columna,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Columna entity.
     *
     */
    public function showAction(Columna $columna)
    {
        $deleteForm = $this->createDeleteForm($columna);

        return $this->render('columna/show.html.twig', array(
            'columna' => $columna,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Columna entity.
     *
     */
    public function editAction(Request $request, Columna $columna)
    {
        $deleteForm = $this->createDeleteForm($columna);
        $editForm = $this->createForm('Cai\ColumnasBundle\Form\ColumnaType', $columna);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            //generar Slug
            $columna->setSlugGenerator($this->get('slug_generator.columna'))
                ->generateSlug();
            $em->persist($columna);
            $em->flush();

            return $this->redirectToRoute('columna_edit', array('id' => $columna->getId()));
        }

        return $this->render('columna/edit.html.twig', array(
            'columna' => $columna,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Columna entity.
     *
     */
    public function deleteAction(Request $request, Columna $columna)
    {
        $form = $this->createDeleteForm($columna);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($columna);
            $em->flush();
        }

        return $this->redirectToRoute('columna_index');
    }

    /**
     * Creates a form to delete a Columna entity.
     *
     * @param Columna $columna The Columna entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Columna $columna)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('columna_delete', array('id' => $columna->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
