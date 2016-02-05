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

        return $this->render('CaiFrontendBundle:profile:my_profile.html.twig', array(
            'categorias'    => $categorias,
            'contacto'  => $contacto,
            'auspicios_1' => $auspicios_1,
            'auspicios_2' => $auspicios_2,
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
                echo $request->request->get('old_password');
                echo $this->getUser()->getPassword();
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
        return $this->render('CaiFrontendBundle:profile:change_user_password.html.twig', array(
            'user' => $user,
            'form' => $form->createView(),
            'contacto'  => $contacto,
            'auspicios_1' => $auspicios_1,
            'auspicios_2' => $auspicios_2,
            'categorias' => $categorias
        ));
    }
}
