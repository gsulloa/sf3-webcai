<?php

namespace Cai\WebBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class AjaxController extends Controller
{
    public function scrollPaginationAction($start){
        $aux = $this->get('cai_web.auxiliar');
        $images = $aux->getImages($start);
        $array = array();
        foreach($images as $image){
            $aux = [
                'id'             => $image->getId(),
                'filenamebinary' => $image->getFilenamebinary(),
                'filename'       => $image->getFilename()
            ];
            $array[] = $aux;
        }
        return new Response(json_encode($array),200);
    }
}
