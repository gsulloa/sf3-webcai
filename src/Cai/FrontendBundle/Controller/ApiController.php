<?php

namespace Cai\FrontendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class ApiController extends Controller
{
    public function eventsAction(){
        $em = $this->getDoctrine()->getManager();
        $eventos = $em->getRepository('CaiWebBundle:Evento')->findAll();
        $eventos_array = array();
        foreach($eventos as $evento){

            $inicio = (new \DateTime($evento->getFechaInicio()->format(\DateTime::ISO8601)))->add(new \DateInterval('PT3H'))->format("Ymd\THis");
            $fin = (new \DateTime($evento->getFechaFin()->format(\DateTime::ISO8601)))->add(new \DateInterval('PT3H30S'))->format("Ymd\THis");
            $url = "https://www.google.com/calendar/render?action=TEMPLATE&ctz=America/Santiago&text=".$evento->getNombre()."&dates=".$inicio."Z/".$fin."Z&details=".$evento->getDescripcion()."&sf=true&output=xml";
            $eventos_array[] = array(
                "start" => date_format($evento->getFechaInicio(), "Y-m-d\TH:i:s"),
                "end"   => date_format($evento->getFechaFin(), "Y-m-d\TH:i:s"),
                "title" => $evento->getNombre(),
                "allDay"=> $evento->getAllDay(),
                "url"   => $url
            );
        }
        return new Response(
            json_encode(
                $eventos_array
            )
        );
    }
}
