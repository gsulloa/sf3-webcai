<?php

namespace Cai\FrontendBundle\Controller;

use Cai\WebBundle\Entity\UserProfile;
use Gulloa\SecurityBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;

class RegistroController extends Controller
{
    /**
     * Creates a new UserProfile entity.
     *
     */
    public function newAction(Request $request)
    {
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirectToRoute('default_target');
        }
        $em = $this->getDoctrine()->getManager();
        $public = $this->get('cai_web.auxiliar')->getPublicInfo();

        $userProfile = new UserProfile();
        $user = new User();
        $form_profile = $this->createForm('Cai\FrontendBundle\Form\RegisterType', $userProfile);
        $form_user = $this->createForm('Cai\FrontendBundle\Form\UserType', $user);
        $form_user->handleRequest($request);
        $userProfile->setUser($user);
        $user->setProfile($userProfile);
        $form_profile->handleRequest($request);

        if($form_profile->isSubmitted()) {
            $claves_coinciden = $user->getPassword() == $request->request->get('second_password');
            if (!$claves_coinciden) {
                $form_user->get('password')->addError(new FormError('Las claves no coinciden'));
            }
            $em = $this->getDoctrine()->getManager();
            $usernameUnico = true;
            if($em->getRepository('GulloaSecurityBundle:User')->findOneByUsername($user->getUsername()) !== null){
                $usernameUnico = false;
                $form_user->get('username')->addError(new FormError('El nombre de usuario ya está utilizado'));
            }
            $usernameCorrecto = true;
            $aux = $this->get('cai_web.auxiliar');
            if($user->getUsername() != $aux->toAscii($user->getUsername(),'','')){
                $usernameCorrecto = false;
                $form_user->get('username')->addError(new FormError('El nombre de usuario no debe contener caracteres especiales, solo letras minusculas y numeros'));
            }
            $rutUnico = true;
            if($em->getRepository('CaiWebBundle:Userprofile')->findOneByRut($userProfile->getRut()) !== null){
                $rutUnico = false;
                $form_profile->get('rut')->addError(new FormError('El RUT ya está utilizado'));
            }
            $rutCorrecto = true;
            $digito = $aux->dv(intval(substr($userProfile->getRut(), 0, -2)));
            if($digito !== strtoupper(substr($userProfile->getRut(), -1))){
                $rutCorrecto = false;
                $form_profile->get('rut')->addError(new FormError('El RUT ingresado es incorrecto'));
            }
            $mail_unique = true;
            if($em->getRepository('CaiWebBundle:Userprofile')->findOneByMail($userProfile->getMail()) !== null){
                $mail_unique = false;
                $form_profile->get('mail')->addError(new FormError('Mail ya utilizado'));
            }
            $mail = true;
            if( !filter_var($userProfile->getMail(), FILTER_VALIDATE_EMAIL) ||
                ((strpos($userProfile->getMail(),"@uc.cl") === false) && (strpos($userProfile->getMail(),"@ing.puc.cl") === false))
            ){
                $mail = false;
                $form_profile->get('mail')->addError(new FormError('Mail con mal formato, o no es mail @uc.cl o @ing.puc.cl'));
            }
            if ($form_profile->isValid() && $form_user->isValid() &&
                $claves_coinciden && $usernameUnico && $rutUnico && $mail && $mail_unique &&
                $usernameCorrecto && $rutCorrecto
            ) {

                $em->persist($userProfile);
                $role = $em->getRepository('GulloaSecurityBundle:Role')->findOneByEtiqueta('ROLE_USER');
                $user->addRole($role);

                $encoder = $this->container->get('security.password_encoder');
                $encoded = $encoder->encodePassword($user, $user->getPassword());
                $user->setPassword($encoded)
                    ->setToken(bin2hex(random_bytes(25)))
                    ->setActivationToken(bin2hex(random_bytes(25)))
                    ->setActive(false)
                ;
                $em->persist($user);
                $em->flush();
                $this->registrationMail($user);
                $session = new Session();
                $session->getFlashBag()->add('success','Te haz registrado correctamente. Revisa tu correo donde te llegará el enlace para activar tu cuenta.');
                return $this->redirectToRoute('login_route');
            }
        }

        return $this->render('CaiFrontendBundle:Registro:new.html.twig', array(
            'userProfile' => $userProfile,
            'user'  => $user,
            'form_profile' => $form_profile->createView(),
            'form_user' => $form_user->createView(),
            'public'    => $public
        ));
    }

    public function activeUserAction($token){
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('GulloaSecurityBundle:User')->findOneBy(array('activation_token' => $token));
        if($user !== null){
            $user->setActivationToken(null)
                ->setActive(1);
            $em->flush();
            $session->getFlashBag()->add('success','Tu cuenta ha sido activada con éxito.');
            return $this->redirectToRoute('login_route');
        }
        $session->getFlashBag()->add('error','El token de activación que ingresaste no existe. Si tu cuenta sigue inactiva por favor contactate con nosotros.');
        return $this->redirectToRoute('login_route');

    }

    public function recoverAction(Request $request){
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirectToRoute('default_target');
        }
        $em = $this->getDoctrine()->getManager();
        $public = $this->get('cai_web.auxiliar')->getPublicInfo();
        $profile = new UserProfile();

        $form_rut = $this->createForm('Cai\FrontendBundle\Form\RecoverRutType', $profile);
        $form_mail = $this->createForm('Cai\FrontendBundle\Form\RecoverMailType', $profile);
        $form_rut->handleRequest($request);
        $form_mail->handleRequest($request);

        $recovering = false;
        $profile_found = null;
        if ($form_rut->isSubmitted() && $form_rut->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $profile_found = $em->getRepository('CaiWebBundle:UserProfile')->findOneByRut($profile->getRut());
            $recovering = true;
        }elseif($form_mail->isSubmitted() && $form_mail->isValid()){
            $em = $this->getDoctrine()->getManager();
            $profile_found = $em->getRepository('CaiWebBundle:UserProfile')->findOneByMail($profile->getMail());
            $recovering = true;
        }
        $session = new Session();
        if($recovering ) {
            if ($profile_found !== null) {
                $password_token = bin2hex(random_bytes(15));
                $profile_found->getUser()->setPasswordToken($password_token);
                $em->flush();
                $this->recoverPasswordMail($profile_found->getUser());
                $session->getFlashBag()->add('success', 'Tu solicitud de recuperar la clave ha sido procesada con éxito. Revisa tu correo donde se indican los pasos que debes seguir');
            } else {
                $session->getFlashBag()->add('error', 'No se ha encontrado un usuario registrado con esos datos.');
            }
        }

        return $this->render('CaiFrontendBundle:recover:form.html.twig', array(
            'profile' => $profile,
            'form_mail' => $form_mail->createView(),
            'form_rut'  => $form_rut->createView(),
            'public'    => $public
        ));
    }

    public function changeAction(Request $request, $token){
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('GulloaSecurityBundle:User')->findOneBy(array(
            'password_token'    => $token
        ));
        if($user === null){
            throw $this->createNotFoundException('Token de cambio no encontrado');
        }

        $form = $this->createForm('Cai\FrontendBundle\Form\ChangePasswordType', $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid() ) {
            $correct_password = $user->getPassword() == $request->request->get('second_password');
            if($correct_password){
                $em = $this->getDoctrine()->getManager();
                $pass = $user->getPassword();
                $encoder = $this->get('security.password_encoder');
                $encoded = $encoder->encodePassword($user, $pass);
                $user->setPassword($encoded)
                    ->setToken(bin2hex(random_bytes(25)))
                    ->setPasswordToken(null)
                ;
                $em->flush();
                $session = new Session();
                $session->getFlashBag()->add('success','El cambio de contraseña se ha realizado correctamente.');
                return $this->redirectToRoute('login_route');
            }
            $form->get('password')->addError(new FormError('Las contraseñas no son iguales'));
        }
        $public = $this->get('cai_web.auxiliar')->getPublicInfo();
        return $this->render('CaiFrontendBundle:profile:change_password.html.twig', array(
            'user' => $user,
            'form' => $form->createView(),
            'public' => $public
        ));
    }

    public function recoverNotAction($token){
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('GulloaSecurityBundle:User')->findOneBy(array(
            'password_token'    => $token
        ));
        if($user === null){
            throw $this->createNotFoundException('Token de cambio no encontrado');
        }
        $user->setPasswordToken(null);
        $em->flush();
        $session = new Session();
        $session->getFlashBag()->add('success','Token de cambio de contraseña eliminado correctamente.');
        return $this->redirectToRoute('login_route');
    }

    private function registrationMail(User $user){
        $params = array(
            "subject"       => "[CAi] Registro con exito !",
            "to"            => $user->getProfile()->getMail(),
            "type"          => "activation",
            "renderParams"  => array(
                "user"  => $user
            )
        );
        $this->get('mailing')->send($params);
    }

    private function recoverPasswordMail(User $user){
        $params = array(
            "subject"       => "[CAi] Recuperar Contraseña",
            "to"            => $user->getProfile()->getMail(),
            "type"          => "recovering",
            "renderParams"  => array(
                "user"  => $user
            )
        );
        $this->get('mailing')->send($params);
    }

}
