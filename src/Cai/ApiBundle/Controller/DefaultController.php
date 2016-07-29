<?php

namespace Cai\ApiBundle\Controller;

use Cai\WebBundle\Utils\Token;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    public function indexAction(){
        $user = $this->getUser()->getUsername();
        return new Response(json_encode(array($user )));
    }
    /*
     * si id es nulo entrega json con todos los eventos
     * si id no es nulo, entrega el evento especificado en caso de encotrarlo
     */
    public function AllEventsAction(){
        return new Response(json_encode($this->get('api.eventos')->getAllEvents()));
    }
    public function MyEventsAction(){
        return new Response(json_encode($this->get('api.eventos')->getMyEvents()));
    }
    public function eventAction($id){
        return new Response(json_encode($this->get('api.eventos')->getEvent($id)));
    }
    public function testAction(){
        return new Response(json_encode(Token::generator("gulloa")));
    }


    public function loginAction($username,$password){
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('CaiApiBundle:User')->findOneByUsername($username);
        if($user === null){
            return new Response(json_encode(array('error'=>'User no encontrado')),404);
        }
        if (!password_verify($password, $user->getPassword())) {
            return new Response(json_encode(array('error'=>"Contraseña incorrecta")),400);
        }
        return new Response(json_encode($user->toArray()));
    }

    public function lastEventsAction($n){
        return new Response(json_encode());
    }
}
