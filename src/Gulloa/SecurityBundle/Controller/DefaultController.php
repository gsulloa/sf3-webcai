<?php

namespace Gulloa\SecurityBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        //
        // COMPROBAR SI USUARIO ESTÁ ACTIVADO
        // TODO: generar authentication checker
        // http://symfony.com/doc/current/cookbook/security/guard-authentication.html
        /*if($this->getUser() !== null){
            if(!$this->getUser()->getActive()){
                return $this->redirectToRoute('logout');
            }
        }*/
        return $this->redirect($this->generateUrl('cai_frontend_homepage'));
    }
}
