<?php

namespace Gulloa\SecurityBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Core\Exception\DisabledException;

class SecurityController extends Controller
{
    public function loginAction(Request $request)
    {
        $contacto = $this->getDoctrine()->getManager()->getRepository('CaiWebBundle:Contacto')->find(1);

        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirect($this->generateUrl('default_target'));
        }
        $authenticationUtils = $this->get('security.authentication_utils');
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        if($error){
            if(get_class($error) == get_class(new BadCredentialsException())){
                $error = ('El nombre de usuario o contraseña es incorrecto');
            }elseif(get_class($error) == get_class(new DisabledException())){
                $error = ('Tu cuenta está inactiva. Por favor revisa tu correo donde te llegará el enlace para activarla.
                                                Si aun no te llega el correo contactanos en ' . $contacto->getMail());
            }else{
                $error = $error->getMessageKey();
            }
            $session = new Session();
            $session->getFlashbag()->add('error',$error);
        }
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render(
            'GulloaSecurityBundle:Security:login.html.twig',
            array(
                // last username entered by the user
                'last_username' => $lastUsername
            )
        );
    }
}