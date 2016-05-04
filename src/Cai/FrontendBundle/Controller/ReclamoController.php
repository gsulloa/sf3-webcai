<?php

namespace Cai\FrontendBundle\Controller;

use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Cai\ReclamosBundle\Entity\Reclamo;
use Cai\ReclamosBundle\Form\ReclamoType;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * Reclamo controller.
 *
 */
class ReclamoController extends Controller
{
    /**
     * Creates a new Reclamo entity.
     *
     */
    public function newAction(Request $request)
    {
        $reclamo = new Reclamo();
        $form = $this->createForm('Cai\ReclamosBundle\Form\ReclamoType', $reclamo);
        $form->handleRequest($request);
        $em = $this->getDoctrine()->getManager();
        $contacto = $em->getRepository('CaiWebBundle:Contacto')->find(1);
        if ($form->isSubmitted()) {
            $domains = array('gmail.com', 'uc.cl', 'ing.puc.cl');
            $i = 0;
            $email = $reclamo->getEmail();
            while($i < sizeof($domains) && preg_match("/$domains[$i]$/", $email) != 1){
                $i++;
            }
            if($i == sizeof($domains)){
                $form->get('email')->addError(new FormError('El mail ingresado no es valido.'));
            }
            if($form->isValid()) {
                $em->persist($reclamo);
                $em->flush();
                $session = new Session();
                $session->getFlashbag()->add('success', "El reclamo ha sido ingresado con éxito. Este será revisado y te contactaremos. Para más consultas no dudes en escribirnos a " . $contacto->getMail() . ".");
                unset($reclamo);
                unset($form);
                $reclamo = new Reclamo();
                $form = $this->createForm('Cai\ReclamosBundle\Form\ReclamoType', $reclamo);
            }
        }
        $public = $this->get('cai_web.auxiliar')->getPublicInfo();
        return $this->render('CaiFrontendBundle:reclamo:new.html.twig', array(
            'reclamo' => $reclamo,
            'public' => $public,
            'form' => $form->createView(),
        ));
    }
}
