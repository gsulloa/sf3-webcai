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
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $query = "SELECT entrada
                  FROM CaiWebBundle:Entrada entrada
                  ORDER BY entrada.fecha DESC";
        $query = $em->createQuery($query);


        $paginator  = $this->get('knp_paginator');
        $entradas = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1)/*page number*/,
            10/*limit per page*/
        );

        return $this->render('CaiWebBundle:entrada:index.html.twig', array(
            'entradas' => $entradas,
        ));
    }

    /**
     * Creates a new Entrada entity.
     *
     */
    public function newAction(Request $request)
    {
        $auxiliar = $this->get('cai_web.auxiliar');
        $entrada = new Entrada();
        $entrada->setFecha(new \DateTime('now'));
        $form = $this->createForm('Cai\WebBundle\Form\EntradaType', $entrada);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            //generar Slug
            $this->generatingSlug($entrada);
            //agregar creado por usuario logeado
            $entrada->setUser($this->getUser());
            //agregar imagen
            $image = $em->getRepository('CaiWebBundle:Imagen')->find(substr($request->request->get('img_slide_0'),6));
            $entrada->setImagen($image);
            $em->persist($entrada);
            $em->flush();

            return $this->redirectToRoute('entrada_show', array('id' => $entrada->getId()));
        }
        $images = $auxiliar->getImages();


        return $this->render('CaiWebBundle:entrada:new.html.twig', array(
            'entrada' => $entrada,
            'form' => $form->createView(),
            'images' => $images,
        ));
    }

    /**
     * Finds and displays a Entrada entity.
     *
     */
    public function showAction(Entrada $entrada)
    {
        $deleteForm = $this->createDeleteForm($entrada);

        return $this->render('CaiWebBundle:entrada:show.html.twig', array(
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
        $auxiliar = $this->get('cai_web.auxiliar');
        $deleteForm = $this->createDeleteForm($entrada);
        $editForm = $this->createForm('Cai\WebBundle\Form\EntradaType', $entrada);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            //generar Slug
            $this->generatingSlug($entrada);
            //agregar imagen
            $image = $em->getRepository('CaiWebBundle:Imagen')->find(substr($request->request->get('img_slide_0'),6));
            $entrada->setImagen($image);
            $em->persist($entrada);
            $em->flush();

            return $this->redirectToRoute('entrada_edit', array('id' => $entrada->getId()));
        }
        $images = $auxiliar->getImages();
        return $this->render('CaiWebBundle:entrada:edit.html.twig', array(
            'entrada' => $entrada,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'images' => $images,
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

    public function publicAction(Entrada $entrada){
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_COMUNICAIONES')
            && !$this->get('security.authorization_checker')->isGranted('ROLE_DIRECTIVA')) {
            return $this->redirectToRoute('entrada_index');
        }
        $entrada->setPublico(!$entrada->getPublico());
        $em = $this->getDoctrine()->getManager();
        $em->flush();
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

    /**
     * @param Entrada $entity
     * Generar Slug unico
     */
    private function generatingSlug(Entrada $entity)
    {
        $em = $this->getDoctrine()->getManager();
        $aux = $this->get('cai_web.auxiliar');
        $slug = $aux->toAscii($entity->getTitulo());
        $querySlug = $slug . '%';
        $entradas = $em->createQuery("
                    SELECT entrada
                    FROM CaiWebBundle:Entrada entrada
                    WHERE entrada.slug LIKE '$querySlug'
            ")->getResult();
        $generateSlug = true;
        for ($i = 0; $i < sizeof($entradas); $i++) {
            if ($entradas[$i]->getId() == $entity->getId()) {
                $generateSlug = false;
            }
            $entradas[$i] = $entradas[$i]->getSlug();
        }
        if ($generateSlug) {
            $slug = $aux->slugGenerator($slug, $entradas);
        } else {
            $slug = $entity->getSlug();
        }
        $entity->setSlug($slug);
        //
    }
}
