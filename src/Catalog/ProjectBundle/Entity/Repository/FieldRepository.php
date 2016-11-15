<?php

namespace Catalog\ProjectBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * ProjectRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class FieldRepository extends EntityRepository
{
    public function getActiveFields(){
        return $this->_em->createQueryBuilder()
                ->select('c')
                ->from("CatalogProjectBundle:Field", "c")
                ->where('c.is_active = true')
                ->orderBy('c.title');
    }
}


