<?php

namespace Cai\FrontendBundle\Controller;

use Cai\WebBundle\Entity\UserProfile;
use Gulloa\SecurityBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;

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
        $form_profile = $this->createForm('Cai\FrontendBundle\Form\UserProfileType', $userProfile);
        $form_user = $this->createForm('Gulloa\SecurityBundle\Form\UserType', $user);
        $form_user->handleRequest($request);
        $userProfile->setUser($user);
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
            $mail = true;
            if( !filter_var($userProfile->getMail(), FILTER_VALIDATE_EMAIL) ||
                ((strpos($userProfile->getMail(),"@uc.cl") === false) && (strpos($userProfile->getMail(),"@ing.puc.cl") === false))
            ){
                $mail = false;
                $form_profile->get('mail')->addError(new FormError('Mail con mal formato, o no es mail @uc.cl o @ing.puc.cl'));
            }

            if ($form_profile->isValid() && $form_user->isValid() && $claves_coinciden && $usernameUnico && $rutUnico && $mail) {
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
}
