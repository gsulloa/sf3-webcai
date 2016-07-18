<?php

namespace Cai\ComunicacionesBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Cai\ComunicacionesBundle\Entity\Material;
use Cai\ComunicacionesBundle\Form\MaterialType;

/**
 * Material controller.
 *
 */
class MaterialController extends Controller
{
    /**
     * Lists all Material entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $public = $this->get('cai_web.auxiliar')->getPublicInfo();
        $materials = $em->getRepository('CaiComunicacionesBundle:Material')->findAll();

        return $this->render('CaiComunicacionesBundle:material:index.html.twig', array(
            'public'    => $public,
            'materials' => $materials,
        ));
    }

    /**
     * Creates a new Material entity.
     *
     */
    public function newAction(Request $request)
    {
        $material = new Material();
        $form = $this->createForm('Cai\ComunicacionesBundle\Form\MaterialType', $material);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($material);
            $em->flush();

            return $this->redirectToRoute('material_show', array('id' => $material->getId()));
        }
        $public = $this->get('cai_web.auxiliar')->getPublicInfo();
        return $this->render('CaiComunicacionesBundle:material:new.html.twig', array(
            'public'    => $public,
            'material' => $material,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Material entity.
     *
     */
    public function showAction(Material $material)
    {
        $deleteForm = $this->createDeleteForm($material);
        $public = $this->get('cai_web.auxiliar')->getPublicInfo();
        return $this->render('CaiComunicacionesBundle:material:show.html.twig', array(
            'public'    => $public,
            'material' => $material,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Material entity.
     *
     */
    public function editAction(Request $request, Material $material)
    {
        $deleteForm = $this->createDeleteForm($material);
        $editForm = $this->createForm('Cai\ComunicacionesBundle\Form\MaterialType', $material);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($material);
            $em->flush();

            return $this->redirectToRoute('material_edit', array('id' => $material->getId()));
        }
        $public = $this->get('cai_web.auxiliar')->getPublicInfo();
        return $this->render('CaiComunicacionesBundle:material:edit.html.twig', array(
            'public'    => $public,
            'material' => $material,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Material entity.
     *
     */
    public function deleteAction(Request $request, Material $material)
    {
        $form = $this->createDeleteForm($material);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($material);
            $em->flush();
        }

        return $this->redirectToRoute('material_index');
    }

    /**
     * Creates a form to delete a Material entity.
     *
     * @param Material $material The Material entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Material $material)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('material_delete', array('id' => $material->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
