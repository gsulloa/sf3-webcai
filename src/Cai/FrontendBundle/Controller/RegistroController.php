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
        if ($this->get('security.authorization_checker')->isGranted('ROLE_USER')) {
            return $this->redirectToRoute('default_target');
        }
        $em = $this->getDoctrine()->getManager();
        $contacto = $em->getRepository('CaiWebBundle:Contacto')->find(1);
        $auspicios_1 = $em->getRepository('CaiWebBundle:Slider')->findOneByTitulo('Auspicios_1');
        $auspicios_2 = $em->getRepository('CaiWebBundle:Slider')->findOneByTitulo('Auspicios_2');

        $userProfile = new UserProfile();
        $user = new User();
        $form_profile = $this->createForm('Cai\FrontendBundle\Form\RegisterType', $userProfile);
        $form_user = $this->createForm('Gulloa\SecurityBundle\Form\UserType', $user);
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
            $rutUnico = true;
            if($em->getRepository('CaiWebBundle:Userprofile')->findOneByRut($userProfile->getRut()) !== null){
                $rutUnico = false;
                $form_profile->get('rut')->addError(new FormError('El RUT ya está utilizado'));
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
            if ($form_profile->isValid() && $form_user->isValid() && $claves_coinciden && $usernameUnico && $rutUnico && $mail && $mail_unique) {

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
            'contacto'  => $contacto,
            'auspicios_1' => $auspicios_1,
            'auspicios_2' => $auspicios_2,
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
        if ($this->get('security.authorization_checker')->isGranted('ROLE_USER')) {
            return $this->redirectToRoute('default_target');
        }

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

        if($recovering && $profile_found !==null){
            $password_token = bin2hex(random_bytes(15));
            $profile_found->getUser()->setPasswordToken($password_token);
            $em->flush();
            $this->recoverPasswordMail($profile_found->getUser());
        }

        return $this->render('CaiFrontendBundle:recover:form.html.twig', array(
            'profile' => $profile,
            'form_mail' => $form_mail->createView(),
            'form_rut'  => $form_rut->createView(),
        ));
    }

    public function changeAction(Request $request, $token){
        if($token === null) {
            $user = $this->getUser();
        }else{
            $em = $this->getDoctrine()->getManager();
            $user = $em->getRepository('GulloaSecurityBundle:User')->findOneBy(array(
                'password_token'    => $token
            ));
            if($user === null){
                throw $this->createNotFoundException('Token de cambio no encontrado');
            }
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
                    ->setToken(bin2hex(random_bytes(50)))
                ;
                $em->flush();
                return $this->redirectToRoute('login_route');
            }
            $form->get('password')->addError(new FormError('Las contraseñas no son iguales'));
        }
        return $this->render('CaiFrontendBundle:profile:change_password.html.twig', array(
            'user' => $user,
            'form' => $form->createView(),
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
        return $this->redirectToRoute('login_route');
    }

    private function registrationMail(User $user){
        $message = \Swift_Message::newInstance()
            ->setSubject('[CAi] Registro con exito !')
            ->setFrom('no-reply@caiuc.cl')
            ->setTo($user->getProfile()->getMail())
            ->setBody(
                $this->renderView(
                // app/Resources/views/Emails/registration.html.twig
                    'CaiFrontendBundle:mailing:activation.html.twig',
                    array('user' => $user)
                ),
                'text/html'
            )
        ;
        $this->get('mailer')->send($message);
    }

    private function recoverPasswordMail(User $user){
        $message = \Swift_Message::newInstance()
            ->setSubject('[CAi] Recuperar Contraseña')
            ->setFrom('no-reply@caiuc.cl')
            ->setTo($user->getProfile()->getMail())
            ->setBody(
                $this->renderView(
                // app/Resources/views/Emails/registration.html.twig
                    'CaiFrontendBundle:mailing:recovering.html.twig',
                    array('user' => $user)
                ),
                'text/html'
            )
        ;
        $this->get('mailer')->send($message);
    }

}
