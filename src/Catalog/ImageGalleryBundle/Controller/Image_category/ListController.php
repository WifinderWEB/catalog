<?php

namespace Catalog\ImageGalleryBundle\Controller\Image_category;

use Admingenerated\CatalogImageGalleryBundle\BaseImage_categoryController\ListController as BaseListController;

/**
 * ListController
 */
class ListController extends BaseListController
{
    protected function processQuery($query)
    {
        $projectId = $this->get('session')->get('project_id');
        $project = $this->getDoctrine()->getRepository('CatalogProjectBundle:Project')->find($projectId);
        $query->andWhere('q.project =:project')
                ->setParameter('project', $project);

        return $query;
    }
}
