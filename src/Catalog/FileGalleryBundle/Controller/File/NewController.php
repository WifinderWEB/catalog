<?php

namespace Catalog\FileGalleryBundle\Controller\File;

use Admingenerated\CatalogFileGalleryBundle\BaseFileController\NewController as BaseNewController;
use Catalog\ProjectBundle\Entity\Repository\ProjectRepository;

/**
 * NewController
 */
class NewController extends BaseNewController
{
    protected function saveObject(\Catalog\FileGalleryBundle\Entity\File $file) {
        if($this->get('session')->get('project_id')){
            $em = $this->getDoctrine()->getManager();
            $project = $em->getRepository('CatalogProjectBundle:Project')->find($this->get('session')->get('project_id'));
            $file->setProject($project);
            $em->persist($file);
            $em->flush();
        }
    }
}
