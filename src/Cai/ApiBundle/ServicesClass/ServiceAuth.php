<?php
/**
 * Created by PhpStorm.
 * User: gulloa
 * Date: 25-07-16
 * Time: 15:37
 */

namespace Cai\ApiBundle\ServicesClass;


class ServiceAuth
{
    private $em;
    public function __construct($em)
    {
        $this->em = $em;
    }

    public function getUser($username){
        return $this->em->getRepository('GulloaSecurityBundle:User')->findOneByUsername($username);
    }
}