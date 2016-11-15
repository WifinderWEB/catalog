<?php

namespace Catalog\VideoGalleryBundle\Controller\Video_category;

use Admingenerated\CatalogVideoGalleryBundle\BaseVideo_categoryController\NewController as BaseNewController;
use Catalog\CatalogBundle\Entity\CatalogProject;

/**
 * NewController
 */
class NewController extends BaseNewController
{
    protected function saveObject(\Catalog\VideoGalleryBundle\Entity\VideoCategory $category) {
        if($this->get('session')->get('project_id')){
            $em = $this->getDoctrine()->getManager();
            $project = $em->getRepository('CatalogProjectBundle:Project')->find($this->get('session')->get('project_id'));
            $category->setProject($project);
            $em->persist($category);
            $em->flush();
        }
    }
}
