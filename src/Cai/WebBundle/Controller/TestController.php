<?php

namespace Cai\WebBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class TestController extends Controller
{
    public function mailAction(){
        $message = \Swift_Message::newInstance()
            ->setSubject('Testing')
            ->setFrom(array('no-reply@caiuc.cl'=> ' No Reply CAi'))
            ->setTo('gsulloa@uc.cl')
            ->setBody(
                    '<h1>Mail de prueba</h1>
<p>Este es un mail de prueba jiji</p>',
                'text/html'
            )
        ;
        $this->get('mailer')->send($message);
        return $this->render('CaiWebBundle:Default:index.html.twig');
    }
}
