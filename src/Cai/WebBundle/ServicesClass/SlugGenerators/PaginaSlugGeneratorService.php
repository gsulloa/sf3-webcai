<?php
/**
 * Created by PhpStorm.
 * User: gulloa
 * Date: 13-09-16
 * Time: 1:02
 */

namespace Cai\WebBundle\ServicesClass\SlugGenerators;


class PaginaSlugGeneratorService extends SlugGeneratorService
{
    protected function getRepository()
    {
        return $this->em->getRepository('CaiWebBundle:Pagina');
    }
}