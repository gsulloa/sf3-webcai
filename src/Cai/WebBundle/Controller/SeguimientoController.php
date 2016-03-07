<?php

namespace Cai\WebBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class SeguimientoController extends Controller
{
    public function indexAction(){
        $em = $this->getDoctrine()->getManager();
        $dql = "
            SELECT seguimiento.etiqueta, count(seguimiento.etiqueta)
            FROM CaiWebBundle:Seguimiento seguimiento
            GROUP BY seguimiento.etiqueta
        ";
        $result = $em->createQuery($dql)->getResult();
        $array = array();
        $total  = 0;
        foreach($result as $element){
            $array[$element['etiqueta']] = $element[1];
            $total += intval($element[1]);
        }
        return $this->render('CaiWebBundle:seguimiento:index.html.twig',array(
            'info'      => $array,
            'total'     => $total
        ));
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
        return $this->render('CaiWebBundle:seguimiento:detail.html.twig',array(
                'detalle'   => $result,
                'type'      => 'entradas'
            ));
    }
    public function paginasAction(){
        $em = $this->getDoctrine()->getManager();
        $dql = "
            SELECT pagina.id,pagina.titulo,pagina.slug, count(pagina.id)
            FROM CaiWebBundle:Seguimiento seguimiento, CaiWebBundle:Pagina pagina
            WHERE seguimiento.etiqueta_id = pagina.id
            AND seguimiento.etiqueta = 'pagina'
            GROUP BY pagina.id
        ";
        $result = $em->createQuery($dql)->getResult();
        return $this->render('CaiWebBundle:seguimiento:detail.html.twig',array(
            'detalle'   => $result,
            'type'      => 'pagina'
        ));
    }
}
