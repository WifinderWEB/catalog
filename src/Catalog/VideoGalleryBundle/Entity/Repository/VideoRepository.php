<?php

namespace Catalog\VideoGalleryBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

class VideoRepository extends EntityRepository
{
    public function GetVideosCategoryId($category_id){
        $query = $this->_em->createQueryBuilder()
            ->select("c")
            ->from("VideoGalleryBundle:Video", "c")
            ->where("c.category_id = :category_id")
            ->setParameter('category_id', $category_id)
            ->getQuery()
            ->getResult();

        return $query;
    }
    
    public function GetGallery($alias){
        $query = $this->_em->createQueryBuilder()
            ->select("g")
            ->from("VideoGalleryBundle:VideoCategory", "g")
            ->innerJoin('g.videos', 'i')
            ->where('c.is_active = true')
            ->andWhere('g.is_active = true');
       
        if($alias) 
            $query = $query->andWhere('c.alias = :alias')
                ->setParameter('alias', $alias)
                ->andWhere('i.is_active = true')
                ->andWhere('c.alias = :alias');

        $query = $query->addOrderBy('i.sort', 'asc')
            ->getQuery()
            ->execute();

        return $query;
    }
    
    public function GetVideoForCategory($alias){
        $query = $this->_em->createQueryBuilder()
            ->select("i")
            ->from("VideoGalleryBundle:Video", "i")
            ->innerJoin('VideoGalleryBundle:VideoCategory', 'c')
            ->where('c.is_active = true')
            ->andWhere('c.alias = :alias')
            ->setParameter('alias', $alias)
            ->andWhere("i.category = c")
            ->andWhere('i.is_active = true')
            ->orderBy('i.sort', 'asc')
            ->getQuery()
            ->setHint(\Doctrine\ORM\Query::HINT_CUSTOM_OUTPUT_WALKER, 'Gedmo\\Translatable\\Query\\TreeWalker\\TranslationWalker')
            ->execute();

        return $query;
    }
}