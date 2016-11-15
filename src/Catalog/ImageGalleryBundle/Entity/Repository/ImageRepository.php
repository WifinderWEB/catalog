<?php

namespace Catalog\ImageGalleryBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use Catalog\NticBundle\Entity\Excurtion;
use Catalog\NticBundle\Entity\Route;

class ImageRepository extends EntityRepository
{
    public function GetImagesCategoryId($category_id){
        $query = $this->_em->createQueryBuilder()
            ->select("c")
            ->from("ImageGalleryBundle:Image", "c")
            ->where("c.category_id = :category_id")
            ->setParameter('category_id', $category_id)
            ->getQuery()
            ->setHint(\Doctrine\ORM\Query::HINT_CUSTOM_OUTPUT_WALKER, 'Gedmo\\Translatable\\Query\\TreeWalker\\TranslationWalker')
            ->getResult();

        return $query;
    }
    
    public function GetGallery($alias){
        $query = $this->_em->createQueryBuilder()
            ->select("g")
            ->from("ImageGalleryBundle:ImageCategory", "g")
            ->innerJoin('g.images', 'i')
            ->where('c.is_active = true')
            ->andWhere('g.is_active = true');
        if($alias) 
            $query = $query->andWhere('c.alias = :alias')
            ->setParameter('alias', $alias)
            ->andWhere('i.is_active = true');
                
        $query = $query->addOrderBy('i.sort', 'asc')
            ->getQuery()
            ->setHint(\Doctrine\ORM\Query::HINT_CUSTOM_OUTPUT_WALKER, 'Gedmo\\Translatable\\Query\\TreeWalker\\TranslationWalker')
            ->execute();

        return $query;
    }
    
    public function GetImagesForCategory($alias){
        $query = $this->_em->createQueryBuilder()
            ->select("i")
            ->from("ImageGalleryBundle:Image", "i")
            ->innerJoin('ImageGalleryBundle:ImageCategory', 'c')
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
