<?php

namespace Cai\WebBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Cai\WebBundle\Entity\Pagina;
use Cai\WebBundle\Form\PaginaType;

/**
 * Pagina controller.
 *
 */
class PaginaController extends Controller
{
    /**
     * Lists all Pagina entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $paginas = $em->getRepository('CaiWebBundle:Pagina')->findAll();

        return $this->render('CaiWebBundle:pagina:index.html.twig', array(
            'paginas' => $paginas,
        ));
    }

    /**
     * Creates a new Pagina entity.
     *
     */
    public function newAction(Request $request)
    {
        $pagina = new Pagina();
        $form = $this->createForm('Cai\WebBundle\Form\PaginaType', $pagina);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            //generar Slug
            $this->generatingSlug($pagina);
            //agregar creado por usuario logeado
            $pagina->setUser($this->getUser());

            $em->persist($pagina);
            $em->flush();

            return $this->redirectToRoute('pagina_show', array('id' => $pagina->getId()));
        }

        return $this->render('CaiWebBundle:pagina:new.html.twig', array(
            'pagina' => $pagina,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Pagina entity.
     *
     */
    public function showAction(Pagina $pagina)
    {
        $deleteForm = $this->createDeleteForm($pagina);
        //informacion de shortcodes
        $aux = $this->get('cai_web.auxiliar');
        $pagina->setCuerpo($aux->getShortcodesInfo($pagina->getCuerpo()));
        //$test = $this->render('CaiFrontendBundle:shortcodes:personas.html.twig')->getContent();
        return $this->render('CaiWebBundle:pagina:show.html.twig', array(
            'pagina' => $pagina,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Pagina entity.
     *
     */
    public function editAction(Request $request, Pagina $pagina)
    {
        $deleteForm = $this->createDeleteForm($pagina);
        $editForm = $this->createForm('Cai\WebBundle\Form\PaginaType', $pagina);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($pagina);
            $em->flush();

            return $this->redirectToRoute('pagina_edit', array('id' => $pagina->getId()));
        }

        return $this->render('CaiWebBundle:pagina:edit.html.twig', array(
            'pagina' => $pagina,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Pagina entity.
     *
     */
    public function deleteAction(Request $request, Pagina $pagina)
    {
        $form = $this->createDeleteForm($pagina);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($pagina);
            $em->flush();
        }

        return $this->redirectToRoute('pagina_index');
    }

    /**
     * Creates a form to delete a Pagina entity.
     *
     * @param Pagina $pagina The Pagina entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Pagina $pagina)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('pagina_delete', array('id' => $pagina->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }

    /**
     * @param Pagina $entity
     * Generar Slug unico
     */
    private function generatingSlug(Pagina $entity)
    {
        $em = $this->getDoctrine()->getManager();
        $aux = $this->get('cai_web.auxiliar');
        $slug = $aux->toAscii($entity->getTitulo());
        $querySlug = $slug . '%';
        $paginas = $em->createQuery("
                    SELECT pagina
                    FROM CaiWebBundle:Pagina pagina
                    WHERE pagina.slug LIKE '$querySlug'
            ")->getResult();
        $generateSlug = true;
        for ($i = 0; $i < sizeof($paginas); $i++) {
            if ($paginas[$i]->getId() == $entity->getId()) {
                $generateSlug = false;
            }
            $paginas[$i] = $paginas[$i]->getSlug();
        }
        if ($generateSlug) {
            $slug = $aux->slugGenerator($slug, $paginas);
        } else {
            $slug = $entity->getSlug();
        }
        $entity->setSlug($slug);
        //
    }
}
