<?php

namespace Catalog\FileGalleryBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Session\Session;

class FileCategoryRepository extends EntityRepository
{
    private $session;

    public function __construct(EntityManager $em, ClassMetadata $class) {
        parent::__construct($em, $class);
        global $kernel;
        if($kernel instanceOf \AppCache) 
            $kernel = $kernel->getKernel();
        $this->session = $kernel->getContainer()->get('session');
    }
    
    public function getListCategory() {
        $q = $this->_em->createQueryBuilder();
        $query = $q->select("c")
                ->from("CatalogFileGalleryBundle:FileCategory", "c")
                ->where('c.project = :project')
                ->setParameter('project', self::getProject())
                ->addOrderBy('c.title');

        return $query;
    }
    
    private function getProject(){
        return $this->_em->getRepository('CatalogProjectBundle:Project')->find($this->session->get('project_id'));
    }
}
