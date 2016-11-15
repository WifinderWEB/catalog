<?php

namespace Catalog\TagBundle\Controller\Tag;

use Admingenerated\CatalogTagBundle\BaseTagController\NewController as BaseNewController;
use Catalog\CatalogBundle\Entity\CatalogProject;

/**
 * NewController
 */
class NewController extends BaseNewController
{
    protected function saveObject(\Catalog\TagBundle\Entity\Tag $tag) {
        if($this->get('session')->get('project_id')){
            $em = $this->getDoctrine()->getManager();
            $project = $em->getRepository('CatalogProjectBundle:Project')->find($this->get('session')->get('project_id'));
            $tag->setProject($project);
            $em->persist($tag);
            $em->flush();
        }
    }
}
