<?php

namespace Catalog\VideoGalleryBundle\Controller\Video_category;

use Admingenerated\CatalogVideoGalleryBundle\BaseVideo_categoryController\ListController as BaseListController;

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
