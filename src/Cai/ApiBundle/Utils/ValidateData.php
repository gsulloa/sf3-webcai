<?php
/**
 * Created by PhpStorm.
 * User: gabrielgallardo
 * Date: 24-07-15
 * Time: 12:05
 */

namespace Cai\ApiBundle\Utils;

class ValidateData {

    public static function validateData($data)
    {
        $data = trim($data);              // Elimina espacio en blanco del inicio y el final de la cadena
        $data = stripslashes($data);      // Quita las barras de un string con comillas escapadas
	    $data = strip_tags($data);        // Retira las etiquetas HTML y PHP de un string
        $data = htmlspecialchars($data);  // Convierte caracteres especiales en entidades HTML
        //$data = htmlentities($data);
        //$data = utf8_decode($data);
	    $data = str_replace(array(':', '\\', '/', '*', "'", '`', '´'), "", $data);
        $data = strtolower($data);

        return $data;
    }
    public static function validateToken($token){
        $token = trim($token);              // Elimina espacio en blanco del inicio y el final de la cadena
        $token = stripslashes($token);      // Quita las barras de un string con comillas escapadas
        $token = strip_tags($token);        // Retira las etiquetas HTML y PHP de un string

        return $token;
    }

}