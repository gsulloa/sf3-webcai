<?php

namespace Cai\WebBundle\Controller;

use Cai\WebBundle\Entity\Imagen;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UploadController extends Controller
{
    public function uploaderAction(){
        return $this->render('CaiWebBundle:uploader:multipleUploader.html.twig');
    }

    public function uploadAction(Request $request){
        $filesSet = $request->query->get('files');
        if(isset($filesSet))
        {
            $em = $this->getDoctrine()->getManager();
            $error = false;
            $files = array();
            $imagenes = array();
            foreach($request->files->all() as $file)
            {
                $imagen = new Imagen();
                $imagen->setFile($file);

                $imagen->preUpload();
                $em->persist($imagen);
                $imagen->upload();
                $em->flush();

                $imagen = $em->getRepository('CaiWebBundle:Imagen')->findOneByFilenamebinary($imagen->getFilenamebinary());

                $imagenes[] = array(
                    'filename' => $imagen->getFilename(),
                    'filenamebinary' => $imagen->getFilenamebinary(),
                    'id' => $imagen->getId()
                );

            }
            $data = ($error) ? array('error' => 'There was an error uploading your files') : array('files' => $files, 'imagenes' => $imagenes);
        }
        else
        {
            $data = array('success' => 'Form was submitted', 'formData' => $request->request->all());
        }

        return new Response(json_encode($data));
    }
}
