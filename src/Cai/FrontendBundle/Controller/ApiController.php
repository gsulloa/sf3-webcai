<?php

namespace Cai\FrontendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Cai\WebBundle\Entity\Evento;

class ApiController extends Controller
{
    public function eventsAction(Request $request){
        $start = $request->query->get('start');
        $end = $request->query->get('end');
        $em = $this->getDoctrine()->getManager();
        $eventos = $em->getRepository('CaiWebBundle:Evento')->findAllBetween($start,$end);
        $eventos_array = array();
        foreach($eventos as $evento){
            $formato = "Ymd\THis";
            $fullDay = false;
            if($evento->getAllDay()) {
                $formato = "Ymd";
                $fullDay = true;
                $date = $evento->getFechaFin();
                date_add($date, date_interval_create_from_date_string('1 days'));
                $evento->setFechaFin($date);
            }
            $inicio = (new \DateTime($evento->getFechaInicio()->format(\DateTime::ISO8601)))->add(new \DateInterval('PT3H'))->format($formato);
            $fin = (new \DateTime($evento->getFechaFin()->format(\DateTime::ISO8601)))->add(new \DateInterval('PT3H30S'))->format($formato);
            if(!$fullDay){
                $inicio = $inicio . 'Z';
                $fin = $fin . 'Z';
            }
            //agregar control AllDay
            $url = "http://www.google.com/calendar/event?action=TEMPLATE&ctz=America/Santiago&text=".$evento->getNombre()."&dates=".$inicio."/".$fin."&details=".$evento->getDescripcion()."&location=".$evento->getLugar()."&trp=false&sprop=name:sf=true";;
            "http://www.google.com/calendar/event?action=TEMPLATE&ctz=America/Santiago&text=".$evento->getNombre()."&dates=".$inicio."/".$fin."&details=".$evento->getDescripcion()."&location=&trp=false &sprop=name:sf=true";

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
