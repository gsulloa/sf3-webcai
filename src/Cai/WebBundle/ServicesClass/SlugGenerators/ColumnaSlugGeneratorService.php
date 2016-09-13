<?php
/**
 * Created by PhpStorm.
 * User: gulloa
 * Date: 13-09-16
 * Time: 1:02
 */

namespace Cai\WebBundle\ServicesClass\SlugGenerators;


class ColumnaSlugGeneratorService extends SlugGeneratorService
{
    protected function getRepository()
    {
        return $this->em->getRepository('CaiColumnasBundle:Columna');
    }
}