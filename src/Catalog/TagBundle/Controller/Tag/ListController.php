<?php

namespace Catalog\TagBundle\Controller\Tag;

use Admingenerated\CatalogTagBundle\BaseTagController\ListController as BaseListController;

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
