<?php

namespace Api\CatalogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Catalog\CatalogBundle\Entity\Repository\CatalogRepository;

class CatalogController extends Controller {

    public function indexAction($name) {
        return $this->render('ApiCatalogBundle:Default:index.html.twig', array('name' => $name));
    }

    /*
     * $project_id - ID проекта
     * $level - уровень каталога
     * 
     * GET parameters
     * parent - выводить родителя
     * 
     * Взвращает категории каталога $level уровня
     */

    public function getCategoriesForLevelAction($project_id, $level = 0) {
        $parentAlias = null;
        $parent = null;
        $items = array();

        if ($p = $this->get('request')->query->get('parent'))
            $parentAlias = $p;

        $catalogItems = $this->getRepository()->getCategoriesForLevel($project_id, $parentAlias, $level);
        if ($catalogItems) {
            $testParent = true;
            foreach ($catalogItems as $c) {
                $items[] = $this->clearItemForListOfNulls($c);

                if (!$parent)
                    $parent = $c->getParent();

                if ($parent->getId() != $c->getParent()->getId())
                    $testParent = false;
            }

            if ($testParent) {
                $parent2 = $this->clearItemForListOfNulls($parent) + $this->clearMetaOfNulls($parent->getMeta());
                if($parent->getContent() && $parent->getContent()->getMeta())
                    $parent2 = array_replace_recursive($parent2, $this->clearMetaOfNulls($parent->getContent()->getMeta()));
            }
            else
                $parent2 = array();

            $result = array('result' => array('items' => $items, 'parent' => $parent2));
        }
        if (!$catalogItems && !$parent) {
            $result = $this->error404();
        }

        return new JsonResponse($result);
    }

    public function getCategoriesForLevelByAliasAction($alias, $level = 0) {
        $parentAlias = null;
        $parent = null;
        $items = array();

        $project = $this->getDoctrine()->getRepository('CatalogProjectBundle:Project')->findOneBy(array(
            'alias' => $alias,
            'is_active' => true
        ));

        if(!$project || $project->getJoinCatalog()->count() == 0)
            $result = $this->error404();
        else {
            $catalogItems = $this->getRepository()->getCategoriesForLevel($project->getId(), $alias, $level);
            if ($catalogItems) {
                $testParent = true;
                foreach ($catalogItems as $c) {
                    $items[] = $this->clearItemForListOfNulls($c);

                    if (!$parent)
                        $parent = $c->getParent();

                    if ($parent->getId() != $c->getParent()->getId())
                        $testParent = false;
                }

                if ($testParent) {
                    $parent2 = $this->clearItemForListOfNulls($parent) + $this->clearMetaOfNulls($parent->getMeta());
                    if ($parent->getContent() && $parent->getContent()->getMeta())
                        $parent2 = array_replace_recursive($parent2, $this->clearMetaOfNulls($parent->getContent()->getMeta()));
                } else
                    $parent2 = array();

                $result = array('result' => array('items' => $items, 'parent' => $parent2));
            }
            if (!$catalogItems && !$parent) {
                $result = $this->error404();
            }

        }
        return new JsonResponse($result);
    }

    /*
     * $project_id - ID проекта
     * $alias - псевдоним каталога
     * 
     * GET parameters 
     * related - выводить связанный товар
     * 
     * Взвращает страницу каталога
     */

    public function getContentByAliasAction($project_id, $alias) {
        $result = array();
        $catalogItem = $this->getRepository()->getItemByAlias($project_id, $alias);

        if ($catalogItem) {
            $item = $this->clearItemOfNulls($catalogItem) + $this->clearMetaOfNulls($catalogItem->getMeta());

            $content = $catalogItem->getContent();

            if ($content && $this->get('request')->query->get('related')) {
                $rel = $this->clearRelatedOfNull($content->getRelated());
                if ($rel)
                    $item = $item + array('related' => $rel);
            }

            if($content && $content->getMeta())
                $item = array_replace_recursive($item, $this->clearMetaOfNulls($content->getMeta()));

            if ($this->get('request')->query->get('img_gallery')) {
                if ($content) {
                    if ($content->getImageGallery()->count() != 0) {
                        $item = $item + array('img_gallery' => $this->clearImageGalleryOfNull($content->getImageGallery()));
                    }
                }
            }
            if ($this->get('request')->query->get('video_gallery')) {
                if ($content) {
                    if ($content->getVideoGallery()->count() != 0) {
                        $item = $item + array('video_gallery' => $this->clearVideoGalleryOfNull($content->getVideoGallery()));
                    }
                }
            }
            if ($this->get('request')->query->get('children')) {
                if ($children = $catalogItem->getChildren()) {
                    if (count($children)) {
                        $item = $item + array('children' => $this->getTree($project_id, $alias, 3));
                    }
                }
            }
            if ($this->get('request')->query->get('sale')) {
                if ($content) {
                    if ($content->getSale()) {
                        $item = $item + array('sale' => $this->clearSaleOfNull($content->getSale()));
                    }
                }
            }

            $result = array('result' => array('item' => $item));
        } else {
            $result = $this->error404();
        }

        return new JsonResponse($result);
    }

    public function getContentByIdsAction($project_id, $ids){
        $items = array();
        if($ids){
            $ids = explode(',', $ids);
            $catalogItems = $this->getRepository()->getItemsByIds($project_id, $ids);
            foreach($catalogItems as $catalogItem) {
                if ($catalogItem) {
                    $item = $this->clearItemOfNulls($catalogItem) + $this->clearMetaOfNulls($catalogItem->getMeta());

                    $content = $catalogItem->getContent();

                    if ($this->get('request')->query->get('related')) {
                        $rel = $this->clearRelatedOfNull($content->getRelated());
                        if ($rel)
                            $item = $item + array('related' => $rel);
                    }

                    if($content && $content->getMeta())
                        $item = array_replace_recursive($item, $this->clearMetaOfNulls($content->getMeta()));

                    if ($this->get('request')->query->get('img_gallery')) {
                        if ($content) {
                            if ($content->getImageGallery()->count() != 0) {
                                $item = $item + array('img_gallery' => $this->clearImageGalleryOfNull($content->getImageGallery()));
                            }
                        }
                    }
                    if ($this->get('request')->query->get('video_gallery')) {
                        if ($content) {
                            if ($content->getVideoGallery()->count() != 0) {
                                $item = $item + array('video_gallery' => $this->clearVideoGalleryOfNull($content->getVideoGallery()));
                            }
                        }
                    }
                    if ($this->get('request')->query->get('sale')) {
                        if ($content) {
                            if ($content->getSale()) {
                                $item = $item + array('sale' => $this->clearSaleOfNull($content->getSale()));
                            }
                        }
                    }
                    if ($this->get('request')->query->get('children')) {
                        if ($children = $catalogItem->getChildren()) {
                            if (count($children)) {
                                $item = $item + array('children' => $this->getTree($project_id, $catalogItem->getAlias(), 3));
                            }
                        }
                    }

                    $items[] = $item;
                }
            }
        }

        if($items)
            $result = array('result' => array('items' => $items));
        else
            $result = $this->error404();

        return new JsonResponse($result);
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

    public function getListAction($project_id) {
        $result = array();
        $alias = null;
        $items = array();
        if ($p = $this->get('request')->query->get('alias'))
            $alias = $p;

        $catalogs = $this->getRepository()->getTree($project_id, $alias);
        if ($catalogs) {
            foreach ($catalogs as $one) {
                $items[] = $this->clearItemForListOfNulls($one);
            }
            $result = array('result' => array('items' => $items));
        } else {
            $result = $this->error404();
        }

        return new JsonResponse($result);
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

    public function getTreeByAliasAction($alias){
        $info = false;
        $brand = $alias;
        if($this->get('request')->query->get('brand'))
            $brand = $this->get('request')->query->get('brand');

        $project = $this->getDoctrine()->getRepository('CatalogProjectBundle:Project')->findOneBy(array(
            'alias' => $brand,
            'is_active' => true
        ));
        if ($p = $this->get('request')->query->get('project_info'))
            $info = true;

        if(!$project || $project->getJoinCatalog()->count() == 0)
            $result = $this->error404();
        else {
            $catalogs = $this->getTree($project->getId(), $alias, 3, true);

            $content = $this->getRepository()->findOneBy(array(
                'alias' => $alias
            ));

            if ($catalogs) {
                $result = array('result' => array('items' => $catalogs));
                $result['result']['content'] = array(
                    'id' => $content->getId(),
                    'title' => $content->getTitle(),
                    'alias' => $content->getAlias()
                );

                if($this->get('request')->query->get('with_filters')){
                    $filters = $this->getFilters($project->getId(), $alias);

                    $result['result']['filters'] = $filters;
                }


                if($content->getContent())
                    $result['result']['content']['content'] = $this->clearContentOfNulls(array(), $content->getContent());
                if($content->getMeta())
                    $result['result']['content'] = $result['result']['content'] + $this->clearMetaOfNulls($content->getMeta());

                if($info)
                    $result['result']['project'] = array(
                        'id' => $project->getId(),
                        'title' => $project->getTitle(),
                        'alias' => $project->getAlias(),
                        'description' => $project->getDescription(),
                        'image_path' => $this->get('request')->getHost() . '' . $project->getWebPath(),
                    );
            } else {
                $result = $this->error404();
            }
        }

        return new JsonResponse($result);
    }

    private function getFilters($project_id, $alias){
        $catalogs = $this->getRepository()->getTree($project_id, $alias, 3);

        $filters = array();

        foreach($catalogs as $one){
            if($content = $one->getContent()){
                if($category = $content->getCategory()){
                    $filters = $this->getFiltersForCategory($filters, $category);
                }
            }
        }

        return $filters;
    }

    private function getFiltersForCategory($filters, $category){

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

    public function getBreadcrumbsAction($project_id, $alias) {
        $items = array();
        $catalogs = $this->getRepository()->getBreadcrumbs($project_id, $alias);

        if ($catalogs) {
            foreach ($catalogs as $one) {
                if ($one->getMeta()->getInBreadcrumbs()){
                    $items[] = array(
                        'id' => $one->getId(),
                        'title' => $one->getTitle(),
                        'alias' => $one->getAlias()
                    );
                }
            }
            $result = array('result' => array('items' => $items));
        } else {
            $result = $this->error404();
        }

        return new JsonResponse($result);
    }

    public function clearRelatedOfNull($content) {
        $result = array();

        foreach ($content as $one) {
            if ($one->getIsActive()) {
                $item = array(
                    'id' => $one->getId(),
                    'title' => $one->getTitle(),
                    'alias' => $one->getAlias()
                );
//                if ($one->getContent())
                    $item = $this->clearContentForListOfNulls($item, $one);
                $result[] = $item;
            }
        }

        return $result;
    }

    public function clearSaleOfNull(\Catalog\ContentBundle\Entity\ContentSale $content){
        $result = array();
        if($content->getPurchasePrice())
            $result['purchase_price'] = $content->getPurchasePrice();
        if($content->getRetailPrice())
            $result['retail_price'] = $content->getRetailPrice();
        if($content->getDiscount())
            $result['discount'] = $content->getDiscount();
        if($content->getVAT())
            $result['VAT'] = $content->getVAT();
        if($content->getWeight())
            $result['weight'] = $content->getWeight();
        if($content->getLength())
            $result['length'] = $content->getLength();
        if($content->getWidth())
            $result['width'] = $content->getWidth();
        if($content->getHeight())
            $result['height'] = $content->getHeight();
        if($content->getBarcode())
            $result['barcode'] = $content->getBarcode();

        return $result;
    }

    public function clearVideoGalleryOfNull($content) {
        $result = array();
        foreach ($content as $one) {
            if ($one->getIsActive()) {
                $item = array();
                $item['id'] = $one->getId();
                $item['title'] = $one->getTitle();
                $item['alias'] = $one->getAlias();
                if ($one->getDescription())
                    $item['description'] = $one->getDescription();
                if ($one->getVideos())
                    $item['videos'] = $this->clearVideoOfNull($one->getVideos());
                $result[] = $item;
            }
        }

        return $result;
    }

    public function clearVideoOfNull($videos) {
        $result = array();
        foreach ($videos as $one) {
            if ($one->getIsActive()) {
                $item = array();
                $item['id'] = $one->getId();
                $item['title'] = $one->getTitle();
                $item['url_video'] = $one->getUrlVideo();
                if ($one->getDescription())
                    $item['description'] = $one->getDescription();
                $item['image_path'] = $this->get('request')->getHost() . '' . $one->getWebPath();
                $result[] = $item;
            }
        }

        return $result;
    }

    public function clearImageGalleryOfNull($content) {
        $result = array();
        foreach ($content as $one) {
            if ($one->getIsActive()) {
                $item = array();
                $item['id'] = $one->getId();
                $item['title'] = $one->getTitle();
                $item['alias'] = $one->getAlias();
                if ($one->getDescription())
                    $item['description'] = $one->getDescription();
                if ($one->getImages())
                    $item['images'] = $this->clearImageOfNull($one->getImages());
                $result[] = $item;
            }
        }

        return $result;
    }

    public function clearImageOfNull($images) {
        $result = array();
        foreach ($images as $one) {
            if ($one->getIsActive()) {
                $item = array();
                $item['id'] = $one->getId();
                $item['title'] = $one->getTitle();
                if ($one->getAlt())
                    $item['alt'] = $one->getAlt();
                if ($one->getDescription())
                    $item['description'] = $one->getDescription();
                $item['image_path'] = $this->get('request')->getHost() . '' . $one->getWebPath();
                $result[] = $item;
            }
        }

        return $result;
    }

    public function clearItemForListOfNulls(\Catalog\CatalogBundle\Entity\Catalog $content) {
        $result = array();
        $result['id'] = $content->getId();
        $result['title'] = $content->getTitle();
        $result['alias'] = $content->getAlias();
        $result['level'] = $content->getLevel();
        if($content->getParent())
            $result['parent_id'] = $content->getParent()->getId();
        if ($content->getContent()) {
            $result = $result + $this->clearContentForListOfNulls($result, $content->getContent());
            if ($content->getContent()->getSale()) {
                $result = $result + array('sale' => $this->clearSaleOfNull($content->getContent()->getSale()));
            }
        }
        return $result;
    }

    public function clearContentForListOfNulls($result, $content) {
        if ($content->getIsActive()) {
            if ($content->getTitle())
                $result['title'] = $content->getTitle();
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
            if ($content->getSale()) {
                $result['sale'] = $this->clearSaleOfNull($content->getSale());
            }
            if($category = $content->getCategory()){
                $result['category'] = array(
                    'id' => $category->getId(),
                    'alias' => $category->getAlias(),
                    'title' => $category->getTitle()
                );
            }
        }
        return $result;
    }

    public function clearItemOfNulls($content) {
        $result = array();
        $result['id'] = $content->getId();
        $result['title'] = $content->getTitle();
        $result['alias'] = $content->getAlias();
        if ($content->getParent())
            $result['parent_id'] = $content->getParent()->getId();
        $result['level'] = $content->getLevel();
        if ($content->getContent())
            $result = $this->clearContentOfNulls($result, $content->getContent());

        return $result;
    }

    private function clearContentOfNulls($result, $content) {
        $result['id'] = $content->getId();
        if ($content->getTitle())
            $result['title'] = $content->getTitle();
        if ($content->getAnons())
            $result['anons'] = $content->getAnons();
        if ($content->getContent())
            $result['content'] = $content->getContent();
        if ($content->getImagePath())
            $result['image_path'] = $this->get('request')->getHost() . '' . $content->getImageWebPath();
        if ($content->getTitleImage())
            $result['title_image'] = $content->getTitleImage();
        if ($content->getAltImage())
            $result['alt_image'] = $content->getAltImage();
        if ($content->getBigImagePath())
            $result['big_image_path'] = $this->get('request')->getHost() . '' . $content->getBigImageWebPath();
        if ($content->getTitleBigImage())
            $result['title_big_image'] = $content->getTitleBigImage();
        if ($content->getAltBigImage())
            $result['alt_big_image'] = $content->getAltBigImage();

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

        return $result;
    }

    private function clearMetaOfNulls($meta) {

        $result = array();
        if ($meta) {
            if ($meta->getMetaTitle())
                $result['title'] = $meta->getMetaTitle();
            if ($meta->getMetaKeywords())
                $result['keywords'] = $meta->getMetaKeywords();
            if ($meta->getMetaDescription())
                $result['description'] = $meta->getMetaDescription();
            if ($meta->getMoreScripts())
                $result['scripts'] = $meta->getMoreScripts();
            if (method_exists($meta, 'getInSiteMap') && $meta->getInSiteMap())
                $result['in_site_map'] = $meta->getInSiteMap();
            if (method_exists($meta, 'getInRobots') && $meta->getInRobots())
                $result['in_robots'] = $meta->getInRobots();
            if (method_exists($meta, 'getInBreadcrumbs') && $meta->getInBreadcrumbs())
                $result['in_breadcrumbs'] = $meta->getInBreadcrumbs();
        }
        return array('meta' => $result);
    }

    private function error404() {
        return array('result' => 'error', 'code' => '404', 'message' => 'Запрашиваемый контент не существует!');
    }

    private function error() {
        return array('result' => 'error');
    }

    private function getRepository() {
        return $this->getDoctrine()->getRepository('CatalogCatalogBundle:Catalog');
    }

    public function getMenuAction($project_id, $alias) {
        $parentAlias = null;
        $parent = null;
        $items = array();

        if ($p = $this->get('request')->query->get('parent'))
            $parentAlias = $p;

        $repo = $this->getDoctrine()->getRepository('CatalogCatalogBundle:Catalog');


        $object = $repo->findOneBy(array('alias' => $alias, 'is_active' => true));

        $child = $repo->getChildByAlias($alias);

        $oneChild = array();
        foreach ($child as $oneLevel) {
            $oneChild[$oneLevel->getAlias()] = array(
                'id' => $oneLevel->getId(),
                'alias' => $oneLevel->getAlias(),
                'title' => $oneLevel->getTitle(),
                'level' => $oneLevel->getLevel());
        }

        $path = $repo->getPath($object);
        if ($path)
            $path = array_slice($path, 1);

        $items = array();
        foreach ($path as $one) {
            $items[$one->getAlias()] = $this->getChild($repo, $one->getParent());
            if ($one->getAlias() == $alias)
                $items[$one->getAlias()][$one->getAlias()]['child'] = $oneChild;
        }

        $pathItems = array();
        $path = array_reverse($path);
        foreach ($path as $one) {
            if ($one->getMeta()->isMenu() && isset($items[$one->getParent()->getAlias()])) {
                $items[$one->getParent()->getAlias()][$one->getParent()->getAlias()]['child'] = $items[$one->getAlias()];
                unset($items[$one->getAlias()]);
            }
        }

        $items = array_shift($items);

        if ($items) {
            $result = array('result' => array('items' => $items));
        } else {
            $result = $this->error404();
        }

        return new JsonResponse($result);
    }

    public function getChild($repo, $parent) {
        $level = $repo->getCatalogOneLevel($parent);
        $items = array();
        foreach ($level as $one) {
            $items[$one->getAlias()] = array(
                'id' => $one->getId(),
                'alias' => $one->getAlias(),
                'title' => $one->getTitle(),
                'level' => $one->getLevel());
        }

        return $items;
    }

    public function searchAction($project_id, $query){
        $items = array();
        $limit = $this->get('request')->query->get('limit');
        if(!$limit)
            $limit = 50;
        $page = $this->get('request')->query->get('page');
        if(!$page)
            $page = 1;

        $catalogs = $this->getRepository()->search($project_id, $query, $limit, $page);

        if ($catalogs['items']) {
            foreach ($catalogs['items'] as $one) {
                $items[] = $this->clearItemForListOfNulls($one);
            }
            $result = array('result' => array('items' => $items, 'count' => $catalogs['count']));
        } else {
            $result = array('result' => array('items' => array(), 'count' => $catalogs['count']));
        }

        return new JsonResponse($result);
    }

}
