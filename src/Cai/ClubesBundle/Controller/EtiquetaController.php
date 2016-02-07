<?php

namespace Cai\ClubesBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Cai\ClubesBundle\Entity\Etiqueta;

/**
 * Etiqueta controller.
 *
 */
class EtiquetaController extends Controller
{
    /**
     * Lists all Etiqueta entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $etiquetas = $em->getRepository('CaiClubesBundle:Etiqueta')->findAll();

        return $this->render('etiqueta/index.html.twig', array(
            'etiquetas' => $etiquetas,
        ));
    }

    /**
     * Finds and displays a Etiqueta entity.
     *
     */
    public function showAction(Etiqueta $etiquetum)
    {

        return $this->render('etiqueta/show.html.twig', array(
            'etiquetum' => $etiquetum,
        ));
    }
}
