<?php

namespace Api\CatalogBundle\Controller;

use Catalog\ContentBundle\Entity\Content;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Catalog\CatalogBundle\Entity\Repository\CatalogRepository;

class CategoryController extends Controller
{

    public function indexAction($name)
    {
        return $this->render('ApiCatalogBundle:Default:index.html.twig', array('name' => $name));
    }

    private function getTree($project_id, $alias, $deep){
        $arLevel = array();

        $catalogs = $this->getRepository()->getTree($project_id, $alias, $deep);

        if ($catalogs) {
            $minLevel = 0;
            foreach ($catalogs as $one) {
                if ($one->getLevel() > 0) {
                    if (!isset($arLevel[$one->getLevel()]))
                        $arLevel[$one->getLevel()] = array();
                    $arLevel[$one->getLevel()][$one->getId()] = $this->clearItemForListOfNulls($one);
                    if ($minLevel == 0 || $minLevel > $one->getLevel())
                        $minLevel = $one->getLevel();
                }
            }

            for ($i = count($arLevel) - 1 + $minLevel; $i >= $minLevel; --$i) {
                foreach ($arLevel[$i] as $id => $item) {
                    if (isset($item['parent_id']) && $i > 1) {
                        if (!isset($arLevel[$i - 1][$item['parent_id']]['child']))
                            $arLevel[$i - 1][$item['parent_id']]['child'] = array();
                        $arLevel[$i - 1][$item['parent_id']]['child'][] = $item;
                    }
                }
            }

            return $arLevel[$minLevel];
        }

        return $arLevel;
    }

    public function searchAction($project_id, $query){
        $items = array();
        $limit = $this->get('request')->query->get('limit');
        if(!$limit)
            $limit = 50;
        $page = $this->get('request')->query->get('page');
        if(!$page)
            $page = 1;

        $catalogs = $this->getDoctrine()->getRepository('CatalogContentBundle:Content')->search($project_id, $query, $limit, $page);

        if ($catalogs['items']) {
            foreach ($catalogs['items'] as $one) {
                $items[] = $this->clearContentForListOfNulls($one);
            }
            $result = array('result' => array('items' => $items, 'count' => $catalogs['count']));
        } else {
            $result = array('result' => array('items' => array(), 'count' => $catalogs['count']));
        }

        return new JsonResponse($result);
    }

    public function clearItemForListOfNulls(\Catalog\CategoryBundle\Entity\Category $content) {
        $result = array();
        $result['id'] = $content->getId();
        $result['title'] = $content->getTitle();
        $result['alias'] = $content->getAlias();
        $result['level'] = $content->getLevel();
        if($content->getParent())
            $result['parent_id'] = $content->getParent()->getId();
        return $result;
    }

    private function error404() {
        return array('result' => 'error', 'code' => '404', 'message' => 'Запрашиваемый контент не существует!');
    }

    private function error() {
        return array('result' => 'error');
    }

    private function getRepository() {
        return $this->getDoctrine()->getRepository('CatalogCategoryBundle:Category');
    }

    public function getTreeAction($project_id) {
        $result = array();
        $alias = null;
        $deep = 0;
        if ($p = $this->get('request')->query->get('alias'))
            $alias = $p;
        if($d = $this->get('request')->query->get('deep'))
            $deep = $d;

        $catalogs = $this->getTree($project_id, $alias, $deep);

        if ($catalogs) {
            $result = array('result' => array('items' => $catalogs));
        } else {
            $result = $this->error404();
        }

        return new JsonResponse($result);
    }

    public function getPathCategoryAction($project_id, $alias){
        $items = array();
        $catalogs = $this->getRepository()->getBreadcrumbs($project_id, $alias);

        if ($catalogs) {
            foreach ($catalogs as $one) {
                $items[] = array(
                    'id' => $one->getId(),
                    'title' => $one->getTitle(),
                    'alias' => $one->getAlias()
                );
            }
            $result = array('result' => array('items' => $items));
        } else {
            $result = $this->error404();
        }

        return new JsonResponse($result);
    }

    public function getCategoryByAliasAction($project_id, $alias){
        $items = array();

        $query = $this->get('request')->query->all();

        $category = $this->getRepository()->getItemByAlias($project_id, $alias, $query);

        if(!$category)
            return new JsonResponse($this->error404());

        if(!$category->getGroups()->count() > 0) {
            $catalogs = $this->getDoctrine()->getRepository('CatalogCatalogBundle:Catalog')->getCategoriesByAlias($project_id, $alias, $query);

            if ($catalogs) {
                foreach ($catalogs as $one) {
                    $items[] = $this->clearItemForListOfNulls($one);
                }
            }

            $result = array('result' => array(
                'items' => $items,
                'type' => 'section'
            ));
        }
        else{
            $filters = $this->getFiltersForCategory($category, $query);
            $content = $this->getContentByCategory($project_id, $category, $query);

            $result = array('result' => array(
                'filters' => $filters,
                'content' => $content['list'],
                'count' => $content['count'],
                'type' => 'category'
            ));
        }

        $result['result']['category'] = array(
            'id' => $category->getId(),
            'alias' => $category->getAlias(),
            'title' => $category->getTitle(),
            'content' => $category->getAnons() ? $category->getAnons() : ''
        );

        return new JsonResponse($result);
    }

    private function getFiltersForCategory($category){
        $filters = array();
        foreach($category->getGroups() as $group){
            if($group->getIsActive()) {
                $parameters = array();

                foreach ($group->getParameter() as $one) {
                    if ($one->getIsActive()) {
                        $parameters[] = array(
                            'id' => $one->getId(),
                            'alias' => $one->getAlias(),
                            'title' => $one->getTitle(),
                            'count' => $one->getContent()->count()
                        );
                    }
                }

                $filters[$group->getId()] = array(
                    'title' => $group->getTitle(),
                    'parameters' => $parameters
                );
            }
        }

        return $filters;
    }

    private function getContentByCategory($projectId, $category, $filter){
        $total = $this->getDoctrine()->getRepository('CatalogContentBundle:Content')->getQueryContentByCategory($projectId, $category, $filter)->select('COUNT(c)')
            ->getQuery()
            ->getSingleScalarResult();

        $content = $this->getDoctrine()->getRepository('CatalogContentBundle:Content')->getQueryContentByCategory($projectId, $category, $filter)->getQuery()->getResult();

        $result = array();
        foreach($content as $one){
            $result[] = $this->clearContentForListOfNulls($one);
        }

        return array('list' => $result, 'count' => $total);
    }

    public function clearContentForListOfNulls(Content $content) {
        $result = array();
        if ($content->getIsActive()) {
            $result['id'] = $content->getId();
            if ($content->getTitle())
                $result['title'] = $content->getTitle();
            if ($content->getAlias())
                $result['alias'] = $content->getAlias();
            if ($content->getAnons())
                $result['anons'] = $content->getAnons();
            if ($content->getImagePath())
                $result['image_path'] = $this->get('request')->getHost() . '' . $content->getImageWebPath();
            if ($content->getTitleImage())
                $result['title_image'] = $content->getTitleImage();
            if ($content->getAltImage())
                $result['alt_image'] = $content->getAltImage();
            foreach ($content->getMoreField() as $key => $one) {
                if ($one)
                    $result[$key] = $one;
            }
            if($category = $content->getCategory()){
                $result['category'] = array(
                    'id' => $category->getId(),
                    'alias' => $category->getAlias(),
                    'title' => $category->getTitle()
                );
            }

            if($sale = $content->getSale()){
                $result['sale'] = array();

                if($sale->getPurchasePrice())
                    $result['sale']['purchase_price'] = $sale->getPurchasePrice();
                if($sale->getRetailPrice())
                    $result['sale']['retail_price'] = $sale->getRetailPrice();
                if($sale->getDiscount())
                    $result['sale']['discount'] = $sale->getDiscount();

                if($sale && $stocks = $sale->getStocks()){
                    $quantity = 0;
                    foreach($stocks as $stock){
                        $quantity = $quantity + ($stock->getQuantity() ? $stock->getQuantity() : 0);
                    }

                    $result['sale']['quantity'] = $quantity;
                }
            }


        }
        return $result;
    }
}