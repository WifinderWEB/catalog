<?php

namespace Catalog\FileGalleryBundle\Controller\File_category;

use Admingenerated\CatalogFileGalleryBundle\BaseFile_categoryController\NewController as BaseNewController;
use Catalog\ProjectBundle\Entity\Repository\ProjectRepository;

/**
 * NewController
 */
class NewController extends BaseNewController
{
    protected function saveObject(\Catalog\FileGalleryBundle\Entity\FileCategory $fileCategory) {
        if($this->get('session')->get('project_id')){
            $em = $this->getDoctrine()->getManager();
            $project = $em->getRepository('CatalogProjectBundle:Project')->find($this->get('session')->get('project_id'));
            $fileCategory->setProject($project);
            $em->persist($fileCategory);
            $em->flush();
        }
    }
}
