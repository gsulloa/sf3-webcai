<?php

namespace Cai\WebBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class SeguimientoController extends Controller
{
    public function indexAction(){
        return $this->render('CaiWebBundle:seguimiento:index.html.twig');
    }

    public function entradasAction(){
        $em = $this->getDoctrine()->getManager();
        $dql = "
            SELECT entrada.id,entrada.titulo,entrada.slug, count(entrada.id)
            FROM CaiWebBundle:Seguimiento seguimiento, CaiWebBundle:Entrada entrada
            WHERE seguimiento.etiqueta_id = entrada.id
            AND seguimiento.etiqueta = 'entrada'
            GROUP BY entrada.id
        ";
        $result = $em->createQuery($dql)->getResult();
        //var_dump($result);exit;
        return $this->render('CaiWebBundle:seguimiento:detail.html.twig',array(
                'detalle'   => $result,
                'type'      => 'entradas'
            ));
    }
}
