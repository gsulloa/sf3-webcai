<?php
/**
 * Created by PhpStorm.
 * User: gulloa
 * Date: 25-07-16
 * Time: 15:37
 */

namespace Cai\ApiBundle\ServicesClass;


use Cai\ApiBundle\Utils\ValidateData;
use Cai\WebBundle\Utils\Rut;
use Symfony\Component\HttpFoundation\RequestStack;

class ServiceAuth
{
    private $em;
    private $request;
    public function __construct($em, RequestStack $request)
    {
        $this->em = $em;
        $this->request = $request->getCurrentRequest();
    }

    public function getUser($username){
        return $this->em->getRepository('GulloaSecurityBundle:User')->findOneByUsername($username);
    }

}