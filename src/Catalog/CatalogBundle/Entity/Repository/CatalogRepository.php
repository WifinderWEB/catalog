<?php

namespace Catalog\CatalogBundle\Entity\Repository;

use Gedmo\Tree\Entity\Repository\NestedTreeRepository;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Session\Session;

class CatalogRepository extends NestedTreeRepository {

    private $session;

    public function __construct(EntityManager $em, ClassMetadata $class) {
        parent::__construct($em, $class);
        global $kernel;
        if($kernel instanceOf \AppCache) 
            $kernel = $kernel->getKernel();
        $this->session = $kernel->getContainer()->get('session');
    }
    

    public function getCategoryWithoutSelf($id) {
        $q = $this->_em->createQueryBuilder();
        $catalogs = self::findCatalogsForProject($this->session->get('project_id'));
        $query = $q->select("c, m")
                ->from("CatalogCatalogBundle:Catalog", "c")
                ->leftJoin('c.meta', 'm')
                ->where($q->expr()->notIn('c.id', $id))
                ->andWhere($q->expr()->in('c.root', $catalogs))
                ->orderBy('c.root', 'ASC')
                ->addOrderBy('c.lft', 'ASC');

        return $query;
    }

    public function getListCategory() {
        $q = $this->_em->createQueryBuilder();
        $catalogs = self::findCatalogsForProject($this->session->get('project_id'));
        if($catalogs) {
            $query = $q->select("c,m")
                ->from("CatalogCatalogBundle:Catalog", "c")
                ->leftJoin('c.meta', 'm')
                ->where($q->expr()->in('c.root', $catalogs))
                ->orderBy('c.root', 'ASC')
                ->addOrderBy('c.lft', 'ASC');
        }
        else{
            $query = $q->select("c,m")
                ->from("CatalogCatalogBundle:Catalog", "c")
                ->leftJoin('c.meta', 'm')
                ->andWhere('c.root is null')
                ->orderBy('c.root', 'ASC')
                ->addOrderBy('c.lft', 'ASC');
        }

        return $query;
    }

    public function findCatalogsForProject($projectId) {

        $q = $this->_em->createQueryBuilder();
        $query = $q->select("c")
                ->from("CatalogCatalogBundle:CatalogProject", "c")
                ->where('c.project_id = :projectId')
                ->setParameter('projectId', $projectId)
                ->getQuery()
                ->getResult();
        $result = array();
        foreach ($query as $one) {
            $result[] = $one->getCatalogId();
        }

        return $result;
    }
    
    public function findCatalogWithItContent($catalog){
        return $this->_em->createQueryBuilder()
                ->select("c, i")
                ->from("CatalogContentBundle:Content", "c")
                ->leftJoin('c.catalogs', 'i')
                ->where('i.id = :id')
                ->setParameter('id', $catalog->getId())
                ->getQuery()
                ->getResult();
    }
    
    //---- API ----
    public function getCategoriesForLevel($project_id, $alias, $level){
        $q = $this->_em->createQueryBuilder();
        $catalogs = self::findCatalogsForProject($project_id);

        $query = $q->select("c, p")
                ->from("CatalogCatalogBundle:Catalog", "c")
                ->leftJoin('c.meta', 'm')
                ->innerJoin ('c.parent', 'p')
                ->where($q->expr()->in('c.root', $catalogs))
                ->andWhere('c.is_active = true')
                ->orderBy('c.root', 'ASC')
                ->addOrderBy('c.lft');
        
        if($alias)
            $query = $query->andWhere('p.alias = :alias')
                ->andWhere('c.level = p.level+1')
                ->setParameter ('alias', $alias);
        else
            $query = $query->andWhere('c.level = :level')
                ->setParameter('level', $level);
        
        return $query->getQuery()
                ->getResult();
    }

    public function getItemByAlias($project_id, $alias){
        $q = $this->_em->createQueryBuilder();
        $catalogs = self::findCatalogsForProject($project_id);

        $query = $q->select("c, m, t, r, i, img, ch")
                ->from("CatalogCatalogBundle:Catalog", "c")
                ->leftJoin('c.meta', 'm')
                ->leftJoin('c.content', 't')
                ->leftJoin('c.children', 'ch')
                ->leftJoin('c.related', 'r')
                ->leftJoin('t.image_gallery', 'i')
                ->leftJoin('i.images', 'img')
                ->where($q->expr()->in('c.root', $catalogs))
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
                ->addOrderBy('ch.lft')
                ->getQuery()
                ->getOneOrNullResult();
        
        return $query;
    }

    public function getItemsByIds($project_id, $ids){
        $q = $this->_em->createQueryBuilder();
        $catalogs = self::findCatalogsForProject($project_id);

        $query = $q->select("c, m, t, r, i, img, ch, s")
            ->from("CatalogCatalogBundle:Catalog", "c")
            ->leftJoin('c.meta', 'm')
            ->leftJoin('c.content', 't')
            ->leftJoin('c.children', 'ch')
            ->leftJoin('c.related', 'r')
            ->leftJoin('t.image_gallery', 'i')
            ->leftJoin('t.sale', 's')
            ->leftJoin('i.images', 'img')
            ->where($q->expr()->in('c.root', $catalogs))
            ->andWhere('c.is_active = true')
            ->andWhere($q->expr()->in('c.id', $ids))
//                ->andWhere('ch.is_active = true')
//                ->andWhere('t.is_active = true')
//                ->andWhere('i.is_active = true')
//                ->andWhere('img.is_active = true')
//                ->andWhere('r.is_active = true')
            ->addOrderBy('i.sort', 'ASC')
            ->addOrderBy('i.id', 'ASC')
            ->addOrderBy('ch.lft')
            ->getQuery()
            ->getResult();

        return $query;
    }
    
    public function getTree($project_id, $alias, $deep = 0){
        $q = $this->_em->createQueryBuilder();
        $catalogs = self::findCatalogsForProject($project_id);

        $query = $q->select("c, t, s")
                ->from("CatalogCatalogBundle:Catalog", "c")
                ->leftJoin('c.content', 't')
                ->leftJoin('t.sale', 's')
                ->where($q->expr()->in('c.root', $catalogs))
                ->andWhere('c.is_active = true');
//                ->andWhere('t.is_active = true');
        if($alias){
            $parent = $this->findOneBy(array('alias' => $alias));
            $query = $query
                ->andWhere('c.lft BETWEEN :lft AND :rgt ')
                ->setParameter('lft', $parent->getLft())
                ->setParameter('rgt', $parent->getRgt());
            if ($deep != 0) {
                $query = $query->andWhere('c.level > :levelMin')
                    ->andWhere('c.level < :levelMax')
                    ->setParameter('levelMin', $parent->getLevel())
                    ->setParameter('levelMax', $parent->getLevel() + $deep);
            }
        }
        else {
            if ($deep != 0) {
                $query = $query->andWhere('c.level <= :deep')->setParameter('deep', $deep);
            }
        }
        $query = $query
                ->addOrderBy('c.root')
                ->addOrderBy('c.lft')
                ->getQuery()
                ->getResult();
                
        return $query;
    }
    
    public function getBreadcrumbs($project_id, $alias){   
        $entity = $this->_em->createQuery(
                "SELECT parent FROM CatalogCatalogBundle:Catalog AS node,
                CatalogCatalogBundle:Catalog AS parent
                LEFT JOIN parent.meta meta
                WHERE node.lft  
                BETWEEN parent.lft 
                AND parent.rgt 
                AND node.alias = :alias 
                AND node.root = parent.root 
                AND parent.level <> 0 "
//                ."AND meta.in_breadcrumbs = true"
                )
                ->setParameter('alias', $alias)
                ->getResult();
        
        return $entity;
    }
    
    public function getChildByAlias($alias){
        $q = $this->_em->createQueryBuilder();

        $query = $q->select("c, m")
                ->from("CatalogCatalogBundle:Catalog", "c")
                ->innerJoin('c.parent', 'p')
                ->leftJoin('c.meta', 'm')
                ->where('p.alias = :alias')
                ->andWhere('c.parent = p')
                ->andWhere('p.is_active = true')
                ->andWhere('c.is_active = true')
                ->andWhere('m.in_menu = true')
                ->setParameter('alias', $alias)
                ->orderBy('c.lft')
                ->getQuery()
                ->getResult();
        
        return $query;
    }
    
    public function getCatalogOneLevel($parent){
        $entity = $this->_em->createQuery(
                "SELECT c FROM CatalogCatalogBundle:Catalog AS c,
                CatalogCatalogBundle:Catalog AS p
                WHERE p.parent = :parent
                AND p.parent = c.parent
                AND c.root = p.root 
                AND c.level = p.level
                AND c.is_active = true
                ")
                ->setParameter('parent', $parent)
                ->getResult();
        
        return $entity;
    }
    
    public function getCatalogsChild($parent){
        $q = $this->_em->createQueryBuilder();

        $query = $q->select("c.id")
                ->from("CatalogCatalogBundle:Catalog", "c")
                ->where('c.root = :root')
                ->andWhere('c.lft > :parent_lft')
                ->andWhere('c.lft < :parent_rgt')
                ->setParameter('parent_lft', $parent->getLft())
                ->setParameter('parent_rgt', $parent->getRgt())
                ->setParameter('root', $parent->getRoot())
                ->orderBy('c.lft')
                ->getQuery()
                ->getArrayResult();
        $result = array();
        $result[] = $parent->getId();
        foreach($query as $one){
            $result[] = $one['id'];
        }
        
        return $result;
    }

    public function search($project_id, $query, $limit, $page){
        $q = $this->_em->createQueryBuilder();
        $catalogs = self::findCatalogsForProject($project_id);

        $offset = $limit * ($page-1);

        $sql = $q->from("CatalogCatalogBundle:Catalog", "c")
            ->leftJoin('c.content', 't')
            ->where($q->expr()->in('c.root', $catalogs))
            ->andWhere('c.is_active = true')
            ->andWhere('c.title LIKE \'%' . $query . '%\' OR t.anons LIKE \'%' . $query . '%\' OR t.content LIKE \'%' . $query . '%\' OR t.more_field LIKE \'%' . $query . '%\'')
            ->addOrderBy('c.root')
            ->addOrderBy('c.lft');

        $query = $sql->addSelect("c, t")
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
