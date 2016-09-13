<?php
namespace Cai\WebBundle\ServicesClass\SlugGenerators;
use Cai\WebBundle\Entity\Publicacion;

/**
 * Created by PhpStorm.
 * User: gulloa
 * Date: 13-09-16
 * Time: 0:48
 */
abstract class SlugGeneratorService
{
    protected $em;
    public function __construct($em)
    {
        $this->em = $em;
    }
    //Genera Slug unico, agregandole un numero en caso de que ya exista
    // ej: ruta, ruta-2, ruta-3....
    protected function numAdder($slug,$array){
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
    protected function toAscii($str, $replace=array(), $delimiter='-') {
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
    protected function stripAccents($str) {
        return strtr(utf8_decode($str), utf8_decode('àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ'), 'aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY');
    }
    public function generateSlug($publicacion){
        $slug = $this->toAscii($publicacion->getTitulo());
        $publicaciones = $this->getRepository()->findLikeSlug($slug);
        $generateSlug = true;
        $i = 0;
        while($i < sizeof($publicaciones) && $generateSlug) {
            if ($publicaciones[$i]->getId() == $publicacion->getId()) {
                $generateSlug = false;
            }else {
                $publicaciones[$i] = $publicaciones[$i]->getSlug();
            }
            $i++;
        }
        if ($generateSlug) {
            $slug = $this->numAdder($slug, $publicaciones);
        }else{
            $slug = $publicacion->getSlug();
        }
        return $slug;
    }
    abstract protected function getRepository();

}