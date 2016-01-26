<?php

namespace Cai\FrontendBundle\Controller;

use Cai\WebBundle\Entity\UserProfile;
use Gulloa\SecurityBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

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
            $mail_unique = false;
            if($em->getRepository('CaiWebBundle:Userprofile')->findOneByRut($userProfile->getMail()) !== null){
                $mail_unique = true;
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
                    ->setToken(bin2hex(random_bytes(50)))
                    ->setActivationToken(bin2hex(random_bytes(50)))
                    ->setActive(false)
                ;
                $this->registrationMail($user);


                $em->persist($user);
                $em->flush();

                return $this->redirectToRoute('login_route');
            }
        }

        return $this->render('CaiFrontendBundle:Registro:new.html.twig', array(
            'userProfile' => $userProfile,
            'user'  => $user,
            'form_profile' => $form_profile->createView(),
            'form_user' => $form_user->createView()
        ));
    }

    public function activeUserAction($token){
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('GulloaSecurityBundle:User')->findOneBy(array('activation_token' => $token));
        if($user !== null){
            $user->setActivationToken('')
                ->setActive(1);
            $em->flush();
            return $this->redirectToRoute('login_route');
        }
        return new Response('');

    }

    public function recoverAction(Request $request){
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
            $new_password = bin2hex(random_bytes(10));
            $encoder = $this->container->get('security.password_encoder');
            $encoded = $encoder->encodePassword($profile_found->getUser(), $new_password);
            $profile_found->getUser()->setPassword($encoded)
                ->setToken(bin2hex(random_bytes(50)))
            ;
            $em->flush();
            $this->recoverPasswordMail($profile_found->getUser(),$new_password);

        }

        return $this->render('CaiFrontendBundle:recover:form.html.twig', array(
            'profile' => $profile,
            'form_mail' => $form_mail->createView(),
            'form_rut'  => $form_rut->createView(),
        ));
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

    private function recoverPasswordMail(User $user, $password){
        $message = \Swift_Message::newInstance()
            ->setSubject('[CAi] Nueva clave')
            ->setFrom('no-reply@caiuc.cl')
            ->setTo($user->getProfile()->getMail())
            ->setBody(
                $this->renderView(
                // app/Resources/views/Emails/registration.html.twig
                    'CaiFrontendBundle:mailing:recovering.html.twig',
                    array('user' => $user,
                        'password' => $password)
                ),
                'text/html'
            )
        ;
        $this->get('mailer')->send($message);
    }

}
