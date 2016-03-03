<?php

namespace Cai\WebBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Cai\WebBundle\Entity\Imagen;
use Cai\WebBundle\Form\ImagenType;

/**
 * Imagen controller.
 *
 */
class ImagenController extends Controller
{
    /**
     * Lists all Imagen entities.
     *
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $imagenes = $em->getRepository('CaiWebBundle:Imagen')->findAll();
        $paginator  = $this->get('knp_paginator');
        $imagenes = $paginator->paginate(
            $imagenes,
            $request->query->getInt('page', 1)/*page number*/,
            24/*limit per page*/
        );
        return $this->render('CaiWebBundle:imagen:index.html.twig', array(
            'imagenes' => $imagenes,
        ));
    }

    /**
     * Creates a new Imagen entity.
     *
     */
    public function newAction(Request $request)
    {
        $imagen = new Imagen();
        $form = $this->createForm('Cai\WebBundle\Form\ImagenType', $imagen);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($imagen);
            $em->flush();

            return $this->redirectToRoute('imagen_show', array('id' => $imagen->getId()));
        }

        return $this->render('CaiWebBundle:imagen:new.html.twig', array(
            'imagen' => $imagen,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Imagen entity.
     *
     */
    public function showAction(Imagen $imagen)
    {
        $deleteForm = $this->createDeleteForm($imagen);

        return $this->render('CaiWebBundle:imagen:show.html.twig', array(
            'imagen' => $imagen,
            'delete_form' => $deleteForm->createView(),
        ));
    }



    /**
     * Deletes a Imagen entity.
     *
     */
    public function deleteAction(Request $request, Imagen $imagen)
    {
        $form = $this->createDeleteForm($imagen);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($imagen);
            $em->flush();
        }

        return $this->redirectToRoute('imagen_index');
    }

    /**
     * Creates a form to delete a Imagen entity.
     *
     * @param Imagen $imagen The Imagen entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Imagen $imagen)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('imagen_delete', array('id' => $imagen->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
