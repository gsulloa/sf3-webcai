<?php

namespace Cai\ClubesBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Cai\ClubesBundle\Entity\Categoria;
use Cai\ClubesBundle\Form\CategoriaType;

/**
 * Categoria controller.
 *
 */
class CategoriaController extends Controller
{
    /**
     * Lists all Categoria entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $categorias = $em->getRepository('CaiClubesBundle:Categoria')->findAll();

        return $this->render('CaiClubesBundle:categoria:index.html.twig', array(
            'categorias' => $categorias,
        ));
    }

    /**
     * Creates a new Categoria entity.
     *
     */
    public function newAction(Request $request)
    {
        $categoria = new Categoria();
        $form = $this->createForm('Cai\ClubesBundle\Form\CategoriaType', $categoria);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $aux = $this->get('cai_web.auxiliar');
            $categoria->setSlug($aux->toAscii($categoria->getEtiqueta()));
            $em->persist($categoria);
            $em->flush();

            return $this->redirectToRoute('clubes_categoria_show', array('id' => $categoria->getId()));
        }

        return $this->render('CaiClubesBundle:categoria:new.html.twig', array(
            'categoria' => $categoria,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Categoria entity.
     *
     */
    public function showAction(Categoria $categoria)
    {
        $deleteForm = $this->createDeleteForm($categoria);

        return $this->render('CaiClubesBundle:categoria:show.html.twig', array(
            'categoria' => $categoria,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Categoria entity.
     *
     */
    public function editAction(Request $request, Categoria $categoria)
    {
        $deleteForm = $this->createDeleteForm($categoria);
        $editForm = $this->createForm('Cai\ClubesBundle\Form\CategoriaType', $categoria);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $aux = $this->get('cai_web.auxiliar');
            $categoria->setSlug($aux->toAscii($categoria->getEtiqueta()));
            $em->persist($categoria);
            $em->flush();

            return $this->redirectToRoute('clubes_categoria_edit', array('id' => $categoria->getId()));
        }

        return $this->render('CaiClubesBundle:categoria:edit.html.twig', array(
            'categoria' => $categoria,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Categoria entity.
     *
     */
    public function deleteAction(Request $request, Categoria $categoria)
    {
        $form = $this->createDeleteForm($categoria);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($categoria);
            $em->flush();
        }

        return $this->redirectToRoute('clubes_categoria_index');
    }

    /**
     * Creates a form to delete a Categoria entity.
     *
     * @param Categoria $categoria The Categoria entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Categoria $categoria)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('clubes_categoria_delete', array('id' => $categoria->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
