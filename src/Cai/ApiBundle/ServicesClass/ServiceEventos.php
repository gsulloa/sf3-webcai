<?php
namespace Cai\ApiBundle\ServicesClass;
/**
 * Created by PhpStorm.
 * User: gulloa
 * Date: 25-07-16
 * Time: 15:20
 */
class ServiceEventos
{
    private $em;
    private $auth;
    public function __construct($em, $auth)
    {
        $this->em = $em;
        $this->auth = $auth;
    }
    public function getMyEvents(){
        $user = $this->auth->getUser('gulloa');
        $eventos = $this->eventosToArray($user->getEventos());
        return $eventos;
    }
    public function getAllEvents(){
        $eventos = $this->eventosToArray($this->em->getRepository('CaiWebBundle:Evento')->findAll());
        return $eventos;
    }
    public function getEvent($id){
        return $this->em->getRepository('CaiWebBundle:Evento')->find($id)->toArray();
    }
    private function eventosToArray($eventos){
        $array_eventos = array();
        foreach($eventos as $evento){
            $array_eventos[] = $evento->toArray();
        }
        return $array_eventos;
    }
}