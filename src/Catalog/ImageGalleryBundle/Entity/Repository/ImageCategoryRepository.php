<?php

namespace Catalog\ImageGalleryBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Session\Session;

class ImageCategoryRepository extends EntityRepository
{
    private $session;

    public function __construct(EntityManager $em, ClassMetadata $class) {
        parent::__construct($em, $class);
        global $kernel;
        if($kernel instanceOf \AppCache) 
            $kernel = $kernel->getKernel();
        $this->session = $kernel->getContainer()->get('session');
    }
    
    public function getListImages(){
        $q = $this->_em->createQueryBuilder();
        $query = $q->select("c")
                ->from("CatalogImageGalleryBundle:ImageCategory", "c")
                ->where('c.project = :project')
                ->setParameter('project', self::getProject())
                ->addOrderBy('c.title');

        return $query;
    }
    
    private function getProject(){
        return $this->_em->getRepository('CatalogProjectBundle:Project')->find($this->session->get('project_id'));
    }
    
    public function retriveImageCategory($request){
        $query = $this->_em->createQueryBuilder()
            ->select("c")
            ->addSelect('i')
            ->from("ImageGalleryBundle:ImageCategory", "c")
            ->leftJoin('c.images', 'i')
            ->addOrderBy('c.id');

        return $query;
    }
}
