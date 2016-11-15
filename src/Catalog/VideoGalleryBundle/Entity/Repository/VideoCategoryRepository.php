<?php

namespace Catalog\VideoGalleryBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Session\Session;

class VideoCategoryRepository extends EntityRepository
{
    private $session;

    public function __construct(EntityManager $em, ClassMetadata $class) {
        parent::__construct($em, $class);
        global $kernel;
        if($kernel instanceOf \AppCache) 
            $kernel = $kernel->getKernel();
        $this->session = $kernel->getContainer()->get('session');
    }
    
    public function getListVideos(){
        $q = $this->_em->createQueryBuilder();
        $query = $q->select("c")
                ->from("CatalogVideoGalleryBundle:VideoCategory", "c")
                ->where('c.project = :project')
                ->setParameter('project', self::getProject())
                ->addOrderBy('c.title');

        return $query;
    }
    
    private function getProject(){
        return $this->_em->getRepository('CatalogProjectBundle:Project')->find($this->session->get('project_id'));
    }
    
    public function retriveVideoCategory($request){
        $query = $this->_em->createQueryBuilder()
            ->select("c")
            ->from("VideoGalleryBundle:VideoCategory", "c")
            ->addOrderBy('c.id');

        return $query;
    }
}
