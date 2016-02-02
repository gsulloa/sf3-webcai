<?php

namespace Cai\WebBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Cai\WebBundle\Entity\Categoria;
use Cai\WebBundle\Form\CategoriaType;

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

        $categorias = $em->getRepository('CaiWebBundle:Categoria')->findAll();

        return $this->render('CaiWebBundle:categoria:index.html.twig', array(
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
        $form = $this->createForm('Cai\WebBundle\Form\CategoriaType', $categoria);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $this->generatingSlug($categoria);
            $em->persist($categoria);
            $em->flush();

            return $this->redirectToRoute('categoria_show', array('id' => $categoria->getId()));
        }

        return $this->render('CaiWebBundle:categoria:new.html.twig', array(
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

        return $this->render('CaiWebBundle:categoria:show.html.twig', array(
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
        $editForm = $this->createForm('Cai\WebBundle\Form\CategoriaType', $categoria);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($categoria);
            $em->flush();

            return $this->redirectToRoute('categoria_edit', array('id' => $categoria->getId()));
        }

        return $this->render('CaiWebBundle:categoria:edit.html.twig', array(
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

        return $this->redirectToRoute('categoria_index');
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
            ->setAction($this->generateUrl('categoria_delete', array('id' => $categoria->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }

    /**
     * @param Categoria $entity
     * Generar Slug unico
     */
    private function generatingSlug(Categoria $entity){
        $em = $this->getDoctrine()->getManager();
        $aux = $this->get('cai_web.auxiliar');
        $slug = $aux->toAscii($entity->getEtiqueta());
        $querySlug = $slug . '%';
        $categorias = $em->createQuery("
                    SELECT categoria
                    FROM CaiWebBundle:Categoria categoria
                    WHERE categoria.slug LIKE '$querySlug'
            ")->getResult();
        $generateSlug = true;
        for($i = 0;$i < sizeof($categorias);$i++){
            if($categorias[$i]->getId() == $entity->getId()){
                $generateSlug = false;
            }
            $categorias[$i] = $categorias[$i]->getSlug();
        }
        if($generateSlug) {
            $slug = $aux->slugGenerator($slug, $categorias);
        }else{
            $slug = $entity->getSlug();
        }
        $entity->setSlug($slug);
        //
    }
}
