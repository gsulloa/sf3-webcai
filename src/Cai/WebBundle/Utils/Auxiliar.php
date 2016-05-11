<?php
/**
 * Created by PhpStorm.
 * User: gsull
 * Date: 28-12-2015
 * Time: 11:17
 */

namespace Cai\WebBundle\Utils;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;

class Auxiliar extends Controller
{
    private $em;
    private $templating;
    public function __construct($entityManager, EngineInterface $templating)
    {
        $this->em = $entityManager;
        $this->templating = $templating;
    }

    //Genera Slug unico, agregandole un numero en caso de que ya exista
    // ej: ruta, ruta-2, ruta-3....
    public function slugGenerator($slug,$array){
        $i = 1;
        if(in_array($slug,$array)) {
            $i++;
            while (in_array($slug . "-" . $i, $array)) {
                $i++;
            }
            $slug = $slug . "-" . $i;
        }
        return $slug;
    }

    public function documentosGet($file){
        $dql = "
            SELECT documento.filename
            FROM CaiWebBundle:Documento documento
            WHERE documento.filename LIKE :filename
            AND documento.filenamebinary = :filenamebinary
            AND documento.id != :id
        ";
        $documentos = $this->em->createQuery($dql)
                        ->setParameter('filename', $file->getFilename() . '%')
                        ->setParameter('filenamebinary', $file->getFilenamebinary())
                        ->setParameter('id', $file->getId() == null ? 0 : $file->getId())
                        ->getResult();
        for($i = 0;$i< sizeof($documentos); $i++){
            $documentos[$i] = $documentos[$i]['filename'];
        }
        return $documentos;

    }

    //http://cubiq.org/the-perfect-php-clean-url-generator
    public function toAscii($str, $replace=array(), $delimiter='-') {
        if( !empty($replace) ) {
            $str = str_replace((array)$replace, ' ', $str);
        }
        //$clean = iconv('UTF-8', 'ASCII//TRANSLIT', $str);
        $clean = $this->stripAccents($str);
        $clean = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $clean);
        $clean =  strtolower(trim($clean, '-'));
        $clean = preg_replace("/[\/_|+ -]+/", $delimiter, $clean);

        return $clean;
    }

    //Funcion que entrega 12 imagenes desde la variable inicio
    public function getImages($inicio = 0){
        $dql = "
            SELECT image
            FROM CaiWebBundle:Imagen image
            ORDER BY image.id DESC
        ";
        $images = $this->em->createQuery($dql)
            ->setMaxResults(12)
            ->setFirstResult($inicio)
            ->getResult();
        return $images;
    }



    public function stripAccents($str) {
        return strtr(utf8_decode($str), utf8_decode('àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ'), 'aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY');
    }

    public function dv($r)
    {
        $s = 1;
        for ($m = 0; $r != 0; $r /= 10)
            $s = ($s + $r % 10 * (9 - $m++ % 6)) % 11;
        return chr($s ? $s + 47 : 75);
    }

    public function getShortcodesInfo($text){
        $aux_text = $text;
        $array = array();
        while(strpos($text,'[[[') !== false){
            //HACER ALGO PARA OBTENER LOS DATOS
            $shortcode = substr($text,3 + strpos($text,'[[['), strpos($text,']]]')- strpos($text,'[[[') - 4 );
            $info = explode(' ', $shortcode);
            $data = array(
                'type'      => $info[0],
                'info'      => array_slice($info,1),
                'shortcode' => '[[['.$shortcode.' ]]]'
            );

            $text = substr($text,3 + strpos($text,']]]'));
            $array[] = $data;
        }
        $array = $this->getRealInfo($array);
        $text = $this->replacingText($aux_text, $array);

        return $text;

    }

    private function getRealInfo($array)
    {
        $new_array = array();
        foreach($array as $data){
            if ($data['type'] == "personas"){
                $data['descripcion_larga'] = "si";
                $data['por_fila'] = "3";
                foreach($data['info'] as $info){
                    $info = explode('=',$info);
                    if($info[0] == 'ids'){
                        $data['personas'] = $this->getPersonas(substr($info[1],1,-1));
                    }else {
                       $data[$info[0]] =  substr($info[1],1,-1);
                    }
                }
            }
            $new_array[] = $data;
        }
        return $new_array;
    }

    private function getPersonas($ids)
    {
        $ids = explode(',',$ids);
        $personas = array();
        foreach($ids as $id) {

            $persona = $this->em->getRepository('CaiWebBundle:Persona')->find($id);
            if($persona){
                $personas[$id] = $persona;
            }
        }
        return $personas;
    }

    private function replacingText($text, $array)
    {
        foreach($array as $data){
            if($data['type'] == 'personas'){
                $content = $this->templating->render('CaiFrontendBundle:shortcodes:personas.html.twig', array(
                    'data'  => $data
                ));
                $text = str_replace($data['shortcode'],$content,$text);
            }
        }
        return $text;
    }

    public function getPublicInfo()
    {
        $contacto = $this->em->getRepository('CaiWebBundle:Contacto')->find(1);
        $categorias = $this->em->getRepository('CaiWebBundle:Categoria')->findAll();
        $auspicios_1 = $this->em->getRepository('CaiWebBundle:Slider')->findOneByTitulo('Auspicios_1');
        $menu = $this->em->getRepository('CaiWebBundle:Menu')->findOneByTitulo('Principal');
        $utiles = $this->em->getRepository('CaiWebBundle:Menu')->findOneByTitulo('Links Utiles');
        return array(
            'contacto'      =>  $contacto,
            'categorias'    =>  $categorias, 
            'auspicios_1'   =>  $auspicios_1, 
            'menu'          =>  $menu,
            'utiles'        =>  $utiles
            );
    }


}