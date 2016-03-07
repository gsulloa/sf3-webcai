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
