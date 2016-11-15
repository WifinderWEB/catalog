<?php

namespace Catalog\ContentBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Session\Session;

class ContentRepository extends EntityRepository
{
    private $session;

    public function __construct(EntityManager $em, ClassMetadata $class) {
        parent::__construct($em, $class);
        global $kernel;
        if($kernel instanceOf \AppCache) 
            $kernel = $kernel->getKernel();
        $this->session = $kernel->getContainer()->get('session');
    }
    
    private function getProject(){
        return $this->_em->getRepository('CatalogProjectBundle:Project')->find($this->session->get('project_id'));
    }
    
    public function getListContents(){
        $q = $this->_em->createQueryBuilder();
        $query = $q->select("c")
                ->from("CatalogContentBundle:Content", "c")
                ->where('c.project = :project')
                ->setParameter('project', self::getProject())
                ->addOrderBy('c.title');

        return $query;
    }
    
    public function getContentForSelectList(){
        $q = $this->_em->createQueryBuilder();
        $query = $q->select("c")
                ->from("CatalogContentBundle:Content", "c")
                ->where('c.project = :project')
                ->setParameter('project', self::getProject())
                ->addOrderBy('c.id');

        return $query;
    }

    public function getContentWithoutSelf($id) {
        $q = $this->_em->createQueryBuilder();
        $query = $q->select("c")
            ->from("CatalogContentBundle:Content", "c")
            ->where($q->expr()->notIn('c.id', $id));

        return $query;
    }

    public function getQueryContentByCategory($projectId, $category, $filter){
        $q = $this->_em->createQueryBuilder();
        $query = $q->select("c, s")
            ->from("CatalogContentBundle:Content", "c")
            ->leftJoin('c.sale', 's')
            ->where('c.project = :project')
            ->andWhere('c.category = :category')
            ->setParameter('project', $projectId)
            ->setParameter('category', $category);

        if($filter){
            if(isset($filter['group_parameters']) && $filter['group_parameters']){
                $groupParams = explode(',', $filter['group_parameters']);

                $query = $query->leftJoin('c.parameters', 'p')
                    ->andWhere($q->expr()->in('p.id', $groupParams));
            }
            if(isset($filter['sort_by']) && $filter['sort_by']){
                if($filter['sort_by'] == 'title_asc'){
                    $query = $query->addOrderBy('c.title', 'ASC');
                }
                if($filter['sort_by'] == 'title_desc'){
                    $query = $query->addOrderBy('c.title', 'DESC');
                }
                if($filter['sort_by'] == 'novelty'){
                    $query = $query->addOrderBy('c.created', 'DESC');
                }
                if($filter['sort_by'] == 'price_asc'){
                    $query = $query->addOrderBy('s.retail_price', 'ASC');
                }
                if($filter['sort_by'] == 'price_desc'){
                    $query = $query->addOrderBy('s.retail_price', 'DESC');
                }
            }
        }
        if(!isset($filter['sort_by'])){
            $query = $query->addOrderBy('c.id');
        }

        return $query;
    }

    public function getItemByAlias($project_id, $alias){
        $q = $this->_em->createQueryBuilder();

        $query = $q->select("c, m, r, i, img")
            ->from("CatalogContentBundle:Content", "c")
            ->leftJoin('c.meta', 'm')
            ->leftJoin('c.related', 'r')
            ->leftJoin('c.image_gallery', 'i')
            ->leftJoin('i.images', 'img')
            ->andWhere('c.is_active = true')
            ->andWhere('c.alias = :alias')
//                ->andWhere('ch.is_active = true')
//                ->andWhere('t.is_active = true')
//                ->andWhere('i.is_active = true')
//                ->andWhere('img.is_active = true')
//                ->andWhere('r.is_active = true')
            ->setParameter('alias', $alias)
            ->addOrderBy('i.sort', 'ASC')
            ->addOrderBy('i.id', 'ASC')
            ->getQuery()
            ->getOneOrNullResult();

        return $query;
    }

    public function search($project_id, $query, $limit, $page){
        $q = $this->_em->createQueryBuilder();

        $offset = $limit * ($page-1);

        $sql = $q->from("CatalogContentBundle:Content", "c")
            ->andWhere('c.is_active = true')
            ->andWhere('c.title LIKE \'%' . $query . '%\' OR c.anons LIKE \'%' . $query . '%\' OR c.content LIKE \'%' . $query . '%\' OR c.more_field LIKE \'%' . $query . '%\'')
            ;

        $query = $sql->addSelect("c")
            ->setMaxResults($limit)
            ->setFirstResult($offset)
            ->getQuery()
            ->getResult();

        $count = $sql->addSelect("COUNT(c) as results")
            ->setFirstResult(0)
            ->getQuery()
            ->getResult();

//        var_dump($count); exit;
        return array('items' => $query, 'count' => $count[0]['results']);
    }
}

