<?php

namespace Cai\WebBundle\Repository;

/**
 * PaginaRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PaginaRepository extends \Doctrine\ORM\EntityRepository
{
    public function findLikeSlug($slug){
        return $this->getEntityManager()
            ->createQuery(
                "SELECT pagina
                FROM CaiWebBundle:Pagina pagina
                WHERE pagina.slug LIKE :slug
            "
            )->setParameter('slug',$slug.'%')
            ->getResult();
    }
}
