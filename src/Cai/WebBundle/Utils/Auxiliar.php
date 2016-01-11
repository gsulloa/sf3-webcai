<?php
/**
 * Created by PhpStorm.
 * User: gsull
 * Date: 28-12-2015
 * Time: 11:17
 */

namespace Cai\WebBundle\Utils;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class Auxiliar extends Controller
{
    private $em;
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

    //http://cubiq.org/the-perfect-php-clean-url-generator
    public function toAscii($str, $replace=array(), $delimiter='-') {
        if( !empty($replace) ) {
            $str = str_replace((array)$replace, ' ', $str);
        }

        $clean = iconv('UTF-8', 'ASCII//TRANSLIT', $str);
        $clean = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $clean);
        $clean = strtolower(trim($clean, '-'));
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

    public function __construct($entityManager)
    {
        $this->em = $entityManager;
    }

}