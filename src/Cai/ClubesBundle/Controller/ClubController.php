<?php

namespace Cai\ClubesBundle\Controller;

use Cai\ClubesBundle\Entity\Etiqueta;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Cai\ClubesBundle\Entity\Club;
use Cai\ClubesBundle\Form\ClubType;

/**
 * Club controller.
 *
 */
class ClubController extends Controller
{
    /**
     * Lists all Club entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $clubs = $em->getRepository('CaiClubesBundle:Club')->findAll();

        return $this->render('CaiClubesBundle:club:index.html.twig', array(
            'clubs' => $clubs,
        ));
    }

    /**
     * Creates a new Club entity.
     *
     */
    public function newAction(Request $request)
    {
        $club = new Club();
        $form = $this->createForm('Cai\ClubesBundle\Form\ClubType', $club);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $aux = $this->get('cai_web.auxiliar');
            $club->setSlug($aux->toAscii($club->getNombre()))
                    ->setAprobado(false);
            $etiquetas = trim($request->request->get('etiquetas'));
            if($etiquetas != ""){
                $etiquetas = explode(',',$etiquetas);
                foreach($etiquetas as $etiqueta){
                    $etiqueta = trim($etiqueta);
                    if($etiqueta != "") {
                        $etiqueta_slug = $aux->toAscii($etiqueta);
                        $etiqueta_found = $em->getRepository('CaiClubesBundle:Etiqueta')->findOneBySlug($etiqueta_slug);
                        if ($etiqueta_found !== null) {
                            $etiqueta = $etiqueta_found;
                        } else {
                            $etiqueta_aux = $etiqueta;
                            $etiqueta = new Etiqueta();
                            $etiqueta->setTitulo($etiqueta_aux)
                                ->setSlug($etiqueta_slug);
                            $em->persist($etiqueta);
                        }
                        $club->addEtiqueta($etiqueta);
                    }
                }
            }
            $em->persist($club);
            $em->flush();

            return $this->redirectToRoute('club_show', array('id' => $club->getId()));
        }

        return $this->render('CaiClubesBundle:club:new.html.twig', array(
            'club' => $club,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Club entity.
     *
     */
    public function showAction(Club $club)
    {
        $deleteForm = $this->createDeleteForm($club);

        return $this->render('CaiClubesBundle:club:show.html.twig', array(
            'club' => $club,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Club entity.
     *
     */
    public function editAction(Request $request, Club $club)
    {
        $deleteForm = $this->createDeleteForm($club);
        $editForm = $this->createForm('Cai\ClubesBundle\Form\ClubType', $club);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $aux = $this->get('cai_web.auxiliar');
            $club->setSlug($aux->toAscii($club->getNombre()));
            $em->persist($club);
            $em->flush();

            return $this->redirectToRoute('club_edit', array('id' => $club->getId()));
        }

        return $this->render('CaiClubesBundle:club:edit.html.twig', array(
            'club' => $club,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Club entity.
     *
     */
    public function deleteAction(Request $request, Club $club)
    {
        $form = $this->createDeleteForm($club);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($club);
            $em->flush();
        }

        return $this->redirectToRoute('club_index');
    }

    /**
     * Creates a form to delete a Club entity.
     *
     * @param Club $club The Club entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Club $club)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('club_delete', array('id' => $club->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
