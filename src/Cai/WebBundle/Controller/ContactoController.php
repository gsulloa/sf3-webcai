<?php

namespace Cai\WebBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Cai\WebBundle\Entity\Contacto;
use Cai\WebBundle\Form\ContactoType;

/**
 * Contacto controller.
 *
 */
class ContactoController extends Controller
{
    /**
     * Lists all Contacto entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $contacto = $em->getRepository('CaiWebBundle:Contacto')->find(1);

        return $this->render('CaiWebBundle:Contacto:index.html.twig', array(
            'contacto' => $contacto,
        ));
    }


    /**
     * Displays a form to edit an existing Contacto entity.
     *
     */
    public function editAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $contacto = $em->getRepository('CaiWebBundle:Contacto')->find(1);
        $editForm = $this->createForm('Cai\WebBundle\Form\ContactoType', $contacto);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {

            $em->persist($contacto);
            $em->flush();

            return $this->redirectToRoute('contacto_edit', array('id' => $contacto->getId()));
        }

        return $this->render('CaiWebBundle:Contacto:edit.html.twig', array(
            'contacto' => $contacto,
            'edit_form' => $editForm->createView(),
        ));
    }


}
