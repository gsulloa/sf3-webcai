<?php

namespace Gulloa\SecurityBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('GulloaSecurityBundle:Default:index.html.twig');
        if ($this->get('security.authorization_checker')->isGranted('ROLE_JEFE_DE_COMISION')) {
            return $this->redirect($this->generateUrl('cai_web_homepage'));
        }
        return $this->redirect($this->generateUrl('cai_frontend_homepage'));
    }
}
