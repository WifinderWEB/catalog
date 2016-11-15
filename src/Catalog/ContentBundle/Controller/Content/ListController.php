<?php

namespace Catalog\ContentBundle\Controller\Content;

use Admingenerated\CatalogContentBundle\BaseContentController\ListController as BaseListController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * ListController
 */
class ListController extends BaseListController {

    protected function getQuery() {
        $query = $this->buildQuery();
        $query = $this->queryProject($query);
        $query = $this->processSort($query);
        $query = $this->processFilters($query);

        return $this->getDoctrine()->getManager()->createQuery($query);
    }

    protected function buildQuery() {
        $query = 'SELECT q FROM CatalogContentBundle:Content q '
                . 'LEFT JOIN q.catalogs cc '
                . 'LEFT JOIN q.project p '
                . 'LEFT JOIN q.tags t ';
        return $query;
    }

    protected function queryProject($query) {
        $projectId = $this->get('session')->get('project_id');
        $project = $this->getDoctrine()->getRepository('CatalogProjectBundle:Project')->find($projectId);
        $query = $query . 'WHERE p.id = ' . $project->getId() . ' ';

        return $query;
    }

    protected function processSort($query) {
        if ($this->getSortColumn()) {
            if (!strstr($this->getSortColumn(), '.')) {
                $query = $query . 'ORDER BY q.' . $this->getSortColumn() . ' ' . $this->getSortOrder() . ' ';
            } else {
                list($table, $column) = explode('.', $this->getSortColumn(), 2);
                $query = $query . 'LEFT JOIN q' . $table . ' ' . $table . ' ORDER BY ' . $table . '.' . $this->getSortColumn() . ' ' . $this->getSortOrder() . ' ';
            }
        }
        return $query;
    }

    protected function processFilters($query) {
        $filterObject = $this->getFilters();

        $t = false;

        if (isset($filterObject['title']) && null !== $filterObject['title']) {
            $query = $query . 'AND q.title LIKE \'%' . $filterObject['title'] . '%\' ';
        }
        if (isset($filterObject['alias']) && null !== $filterObject['alias']) {
            $query = $query . 'AND q.alias LIKE \'%' . $filterObject['alias'] . '%\' ';
        }
        if (isset($filterObject['catalogs']) && null !== $filterObject['catalogs']) {
            $query = $query . $this->addCollectionCatalogFilter($filterObject['catalogs']);
        }
        if (isset($filterObject['tags']) && null !== $filterObject['tags']) {
            $query = $query . $this->addCollectionTagsFilter($filterObject['tags']);
        }
        if (isset($filterObject['is_active']) && null !== $filterObject['is_active']) {
            $query = $query . 'AND q.is_active = ' . $filterObject['is_active'] . ' ';
        }
        return $query;
    }

    public function addCollectionCatalogFilter($value) {
        $catalogIds = $this->getDoctrine()->getRepository('CatalogCatalogBundle:Catalog')->getCatalogsChild($value);
        $query = 'AND cc.id in(' . implode(',', $catalogIds) . ') ';

        return $query;
    }

    public function addCollectionTagsFilter($value) {
        $query = 'AND t.id = ' . $value->getId();

        return $query;
    }

    public function cloneAction($id) {
        $content = $this->getDoctrine()->getRepository('CatalogContentBundle:Content')->find($id);

        if (!$content) {
            throw new NotFoundHttpException("The Catalog\ContentBundle\Entity\Content with id $id can't be found");
        }

        $clone = clone $content;
        $alias = $content->getAlias() . '__clone';
        $title = $content->getTitle() . ' [копия]';
        for ($i = 0; $i <= 100; $i++) {
            if ($i != 0)
                $alias = $content->getAlias() . '__clone' . '_' . $i;
            $test = $this->getDoctrine()->getRepository('CatalogContentBundle:Content')->findOneBy(array('alias' => $alias));
            if (!$test) {
                $title = $content->getTitle() . ' [копия ' . $i . ']';
                break;
            }
        }
        
        $clone->setAlias($alias);
        $clone->setTitle($title);

        $em = $this->getDoctrine()->getManagerForClass('Catalog\ContentBundle\Entity\Content');
        $em->detach($clone);
        $em->persist($clone);
        $em->flush();

        $this->get('session')->getFlashBag()->add('success', $this->get('translator')->trans("action.object.edit.success", array(), 'Admingenerator'));

        return new RedirectResponse($this->generateUrl("Catalog_ContentBundle_Content_edit", array('pk' => $clone->getId())));
    }

}
