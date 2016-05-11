<?php

namespace Cai\WebBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class TestController extends Controller
{
    public function mailAction(){
        $em = $this->getDoctrine()->getManager();
        $images = $em->getRepository('CaiWebBundle:Imagen')->findAll();
        foreach ($images as $image) {
            $imagen = $image->getUploadRootDir() . $image->getFilename();
            $image_mobile = $image->getUploadRootDir() . 'mobile-' . $image->getFilename();
            $crop_images = [
                [
                    'final_name'    => $image_mobile,
                    'width'         => 300,
                    'height'        => 300
                ]
            ];
            $imgEditor = $this->get('cai_web.images');
            $imgEditor->crop($imagen, $crop_images);
        }


        return $this->render('CaiWebBundle:Default:index.html.twig');
    }

}
