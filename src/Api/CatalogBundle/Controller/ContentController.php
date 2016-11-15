<?php

namespace Api\CatalogBundle\Controller;

use Catalog\ContentBundle\Entity\Content;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Catalog\CatalogBundle\Entity\Repository\CatalogRepository;

class ContentController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('ApiCatalogBundle:Default:index.html.twig', array('name' => $name));
    }

    public function getContentByAliasAction($project_id, $alias) {
        $result = array();
        $content = $this->getRepository()->getItemByAlias($project_id, $alias);

        if ($content) {
            $item = $this->clearContentOfNulls($result, $content) + $this->clearMetaOfNulls($content->getMeta());

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

            $result = array('result' => array('item' => $item));
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


    private function clearContentOfNulls($result, $content) {
        $result['id'] = $content->getId();
        if ($content->getTitle())
            $result['title'] = $content->getTitle();
        if ($content->getAlias())
            $result['alias'] = $content->getAlias();
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

    private function error404() {
        return array('result' => 'error', 'code' => '404', 'message' => 'Запрашиваемый контент не существует!');
    }

    private function error() {
        return array('result' => 'error');
    }

    private function getRepository() {
        return $this->getDoctrine()->getRepository('CatalogContentBundle:Content');
    }
}