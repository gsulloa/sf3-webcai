<?php

namespace Cai\ColumnasBundle\Repository;

/**
 * ColumnaRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ColumnaRepository extends \Doctrine\ORM\EntityRepository
{
    public function findLikeSlug($slug){
        return $this->getEntityManager()
            ->createQuery(
                "SELECT col
                FROM CaiColumnasBundle:Columna col
                WHERE REGEXP(col.slug, :slug) = TRUE 
            "
            )->setParameter('slug',"^$slug-*[0-9]*$")
            ->getResult();
    }
}
