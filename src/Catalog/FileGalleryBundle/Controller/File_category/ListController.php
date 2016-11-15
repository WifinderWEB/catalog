<?php

namespace Catalog\FileGalleryBundle\Controller\File_category;

use Admingenerated\CatalogFileGalleryBundle\BaseFile_categoryController\ListController as BaseListController;
use Catalog\ProjectBundle\Entity\Repository\ProjectRepository;

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
