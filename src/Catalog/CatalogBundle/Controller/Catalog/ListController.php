<?php

namespace Catalog\CatalogBundle\Controller\Catalog;

use Admingenerated\CatalogCatalogBundle\BaseCatalogController\ListController as BaseListController;

/**
 * ListController
 */
class ListController extends BaseListController {

    protected function processSort($query) {
        if ($this->getSortColumn()) {
            if (!strstr($this->getSortColumn(), '.')) { //direct column
                $query->addOrderBy('q.' . $this->getSortColumn(), $this->getSortOrder());
            } else {
                list($table, $column) = explode('.', $this->getSortColumn(), 2);
                $this->addJoinFor($table, $query);
                $query->addOrderBy($this->getSortColumn(), $this->getSortOrder());
            }
        }
        $query->addOrderBy('q.root, q.lft', 'ASC');
    }

    protected function processQuery($query) {
        $projectId = $this->get('session')->get('project_id');
        $catalogs = $this->getDoctrine()->getRepository('CatalogCatalogBundle:Catalog')->findCatalogsForProject($projectId);
        if (count($catalogs) > 0)
            $query->andWhere('q.root in (' . join(',', $catalogs) . ')');
        else
            $query->andWhere('q.root is null');
        return $query;
    }

    public function moveUpAction($id) {
        $repo = $this->getDoctrine()->getRepository('CatalogCatalogBundle:Catalog');
        $item = $repo->find($id);
        $t = $repo->moveUp($item, 1);
        if ($repo->verify() === true)
            $repo->recover();
        if ($t)
            $this->get('session')->getFlashBag()->add('success', 'Элемент успешно перемещен');
        else
            $this->get('session')->getFlashBag()->add('error', 'Не возможно переместить элемент');
        return $this->redirect($this->generateUrl("Catalog_CatalogBundle_Catalog_list"));
    }

    public function moveDownAction($id) {
        $repo = $this->getDoctrine()->getRepository('CatalogCatalogBundle:Catalog');
        $item = $repo->find($id);
        $t = $repo->moveDown($item, 1);
        if ($repo->verify() === true)
            $repo->recover();
        if ($t)
            $this->get('session')->getFlashBag()->add('success', 'Элемент успешно перемещен');
        else
            $this->get('session')->getFlashBag()->add('error', 'Не возможно переместить элемент');

        return $this->redirect($this->generateUrl("Catalog_CatalogBundle_Catalog_list"));
    }

    protected function processFilters($query) {
        $filterObject = $this->getFilters();

        $queryFilter = $this->getQueryFilter();
        $queryFilter->setQuery($query);

        if (isset($filterObject['parent']) && null !== $filterObject['parent']) {
            $catalogIds = $this->getDoctrine()->getRepository('CatalogCatalogBundle:Catalog')->getCatalogsChild($filterObject['parent']);
            $queryFilter->getQuery()->andWhere('q.id IN ('.implode(',', $catalogIds).')');
        }
        if (isset($filterObject['title']) && null !== $filterObject['title']) {
            $queryFilter->addStringFilter('title', $filterObject['title']);
        }
        if (isset($filterObject['alias']) && null !== $filterObject['alias']) {
            $queryFilter->addStringFilter('alias', $filterObject['alias']);
        }
        if (isset($filterObject['is_active']) && null !== $filterObject['is_active']) {
            $queryFilter->addBooleanFilter('is_active', $filterObject['is_active']);
        }
    }

}
