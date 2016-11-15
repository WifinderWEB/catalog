<?php

namespace Catalog\ContentBundle\Controller\Content;

use Admingenerated\CatalogContentBundle\BaseContentController\NewController as BaseNewController;
use Catalog\CatalogBundle\Entity\CatalogProject;
use Catalog\ContentBundle\Entity\ContentSale;
use Catalog\StockBundle\Entity\StockContent;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * NewController
 */
class NewController extends BaseNewController {

    protected function getNewObject() {
        $obj = new \Catalog\ContentBundle\Entity\Content;
        $em = $this->getDoctrine()->getManager();
        $project = $em->getRepository('CatalogProjectBundle:Project')->find($this->get('session')->get('project_id'));

        $stocks = $em->getRepository('CatalogStockBundle:Stock')->findBy(array('is_active' => true));
        $newSale = new ContentSale();
        $obj->setSale($newSale);
        foreach($stocks as $one){
            $newStock = new StockContent();
            $newStock->setStock($one);
            $newSale->addStock($newStock);
        }

        $obj->setProject($project);
        return $obj;
    }

    protected function saveObject(\Catalog\ContentBundle\Entity\Content $Content) {
        $em = $this->getDoctrine()->getManager();
        $stocks = $Content->getSale()->getStocks();

        $temp = new ArrayCollection();
        foreach($stocks as $one){
            $temp->add($one);
        }
        $Content->getSale()->setStocks(null);

        $em->persist($Content);
        $em->flush();

        $Content->getSale()->setStocks($temp);
        $em->persist($Content);
        $em->flush();
    }

}
