<?php
/**
 * Created by PhpStorm.
 * User: gulloa
 * Date: 26-07-16
 * Time: 13:18
 */

namespace Cai\WebBundle\Utils;


class Token
{
    CONST length = 30;
    static public function generator($string = null){
        if($string === null)
            return bin2hex(random_bytes(self::length));
        return sha1(md5($string));
    }

}