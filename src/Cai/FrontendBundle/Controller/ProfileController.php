<?php

namespace Cai\FrontendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

class ProfileController extends Controller
{
    public function myProfileAction(){
        $em = $this->getDoctrine()->getManager();
        $contacto = $em->getRepository('CaiWebBundle:Contacto')->find(1);
        $categorias = $em->getRepository('CaiWebBundle:Categoria')->findAll();
        $auspicios_1 = $em->getRepository('CaiWebBundle:Slider')->findOneByTitulo('Auspicios_1');
        $auspicios_2 = $em->getRepository('CaiWebBundle:Slider')->findOneByTitulo('Auspicios_2');
        $menu = $em->getRepository('CaiWebBundle:Menu')->findOneByTitulo('Principal');

        return $this->render('CaiFrontendBundle:profile:my_profile.html.twig', array(
            'categorias'    => $categorias,
            'contacto'  => $contacto,
            'auspicios_1' => $auspicios_1,
            'auspicios_2' => $auspicios_2,
            'menu'        => $menu
        ));
    }

    public function changePasswordAction(Request $request){
        $oldPassword = $this->getUser()->getPassword();
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $form = $this->createForm('Cai\FrontendBundle\Form\ChangePasswordType', $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid() ) {
            $correct_password = true;
            if($user->getPassword() != $request->request->get('second_password')){
                $correct_password = false;
                $form->get('password')->addError(new FormError('Las contraseñas no son iguales'));
            }
            $old = true;
            if(!password_verify($request->request->get('old_password'),$oldPassword)){
                $old = false;
                $form->get('password')->addError(new FormError('No haz ingresado correctamente tu contraseña actual'));
            }
            if($correct_password && $old){
                $pass = $user->getPassword();
                $encoder = $this->get('security.password_encoder');
                $encoded = $encoder->encodePassword($user, $pass);
                $user->setPassword($encoded)
                    ->setToken(bin2hex(random_bytes(25)))
                ;
                $em->flush();
                $session = new Session();
                $session->getFlashBag()->add('success','El cambio de contraseña se ha realizado correctamente.');
            }
        }
        $contacto = $em->getRepository('CaiWebBundle:Contacto')->find(1);
        $categorias = $em->getRepository('CaiWebBundle:Categoria')->findAll();
        $auspicios_1 = $em->getRepository('CaiWebBundle:Slider')->findOneByTitulo('Auspicios_1');
        $auspicios_2 = $em->getRepository('CaiWebBundle:Slider')->findOneByTitulo('Auspicios_2');
        $menu = $em->getRepository('CaiWebBundle:Menu')->findOneByTitulo('Principal');
        return $this->render('CaiFrontendBundle:profile:change_user_password.html.twig', array(
            'user' => $user,
            'form' => $form->createView(),
            'contacto'  => $contacto,
            'auspicios_1' => $auspicios_1,
            'auspicios_2' => $auspicios_2,
            'categorias' => $categorias,
            'menu'  => $menu
        ));
    }
    public function changeInfoAction(Request $request){
        $oldPassword = $this->getUser()->getPassword();
        $em = $this->getDoctrine()->getManager();
        $profile = $this->getUser()->getProfile();
        $user = $this->getUser();
        $form_cat = $this->createForm('Cai\FrontendBundle\Form\ChangeCategoriasType', $user);
        $form = $this->createForm('Cai\FrontendBundle\Form\ChangeProfileType', $profile);
        $form->handleRequest($request);
        $form_cat->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid() && $form_cat->isValid() ) {
            $old = true;
            if(!password_verify($request->request->get('old_password'),$oldPassword)){
                $old = false;
                $session = new Session();
                $session->getFlashBag()->add('error','No haz ingresado correctamente tu contraseña actual');
            }
            $rutUnico = true;
            $aux_user = $em->getRepository('CaiWebBundle:Userprofile')->findOneByRut($profile->getRut());
            if($aux_user !== null ){
                if($aux_user->getUser() !== $this->getUser()) {
                    $rutUnico = false;
                    $form->get('rut')->addError(new FormError('El RUT ya está utilizado'));
                }
            }
            $aux = $this->get('cai_web.auxiliar');
            $rutCorrecto = true;
            $digito = $aux->dv(intval(substr($profile->getRut(), 0, -2)));
            if($digito !== strtoupper(substr($profile->getRut(), -1))){
                $rutCorrecto = false;
                $form->get('rut')->addError(new FormError('El RUT ingresado es incorrecto'));
            }
            if($old && $rutUnico && $rutCorrecto){
                $em->persist($profile);
                $em->persist($user);
                $em->flush();
                $session = new Session();
                $session->getFlashBag()->add('success','El cambio de la información se ha completado con éxito.');
            }
        }
        $contacto = $em->getRepository('CaiWebBundle:Contacto')->find(1);
        $categorias = $em->getRepository('CaiWebBundle:Categoria')->findAll();
        $auspicios_1 = $em->getRepository('CaiWebBundle:Slider')->findOneByTitulo('Auspicios_1');
        $auspicios_2 = $em->getRepository('CaiWebBundle:Slider')->findOneByTitulo('Auspicios_2');
        $menu = $em->getRepository('CaiWebBundle:Menu')->findOneByTitulo('Principal');
        return $this->render('CaiFrontendBundle:profile:change_user_info.html.twig', array(
            'profile' => $profile,
            'user'    => $user,
            'form' => $form->createView(),
            'form_cat' => $form_cat->createView(),
            'contacto'  => $contacto,
            'auspicios_1' => $auspicios_1,
            'auspicios_2' => $auspicios_2,
            'categorias' => $categorias,
            'menu'  => $menu
        ));
    }

    public function changePhotoAction(Request $request){
        $oldPassword = $this->getUser()->getPassword();
        $em = $this->getDoctrine()->getManager();
        $profile = $this->getUser()->getProfile();
        $form = $this->createForm('Cai\FrontendBundle\Form\ChangePhotoType', $profile);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid() ) {
            $old = true;
            if(!password_verify($request->request->get('old_password'),$oldPassword)){
                $old = false;
                $session = new Session();
                $session->getFlashBag()->add('error','No haz ingresado correctamente tu contraseña actual');
            }
            if($old){
                $em->persist($profile);
                $em->flush();
                $session = new Session();
                $session->getFlashBag()->add('success','El cambio de foto de perfil se ha completado con éxito.');
            }
        }
        $contacto = $em->getRepository('CaiWebBundle:Contacto')->find(1);
        $categorias = $em->getRepository('CaiWebBundle:Categoria')->findAll();
        $auspicios_1 = $em->getRepository('CaiWebBundle:Slider')->findOneByTitulo('Auspicios_1');
        $auspicios_2 = $em->getRepository('CaiWebBundle:Slider')->findOneByTitulo('Auspicios_2');
        $menu = $em->getRepository('CaiWebBundle:Menu')->findOneByTitulo('Principal');
        return $this->render('CaiFrontendBundle:profile:change_user_photo.html.twig', array(
            'profile' => $profile,
            'form' => $form->createView(),
            'contacto'  => $contacto,
            'auspicios_1' => $auspicios_1,
            'auspicios_2' => $auspicios_2,
            'categorias' => $categorias,
            'menu'  => $menu
        ));
    }
}
