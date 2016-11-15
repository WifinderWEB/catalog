<?php

namespace Catalog\ContentBundle\Controller\Content;

use Admingenerated\CatalogContentBundle\BaseContentController\EditController as BaseEditController;
use Catalog\ContentBundle\Entity\ContentSale;
use Catalog\StockBundle\Entity\StockContent;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * EditController
 */
class EditController extends BaseEditController
{
    protected function getObject($pk)
    {
        $em = $this->getDoctrine()->getManager();
        $content = $this->getQuery($pk)->getOneOrNullResult();

        $stocks = $em->getRepository('CatalogStockBundle:Stock')->findBy(array('is_active' => true));
        $newSale = $content->getSale();
        if(!$newSale) {
            $newSale = new ContentSale();
            $content->setSale($newSale);
        }
        foreach($stocks as $one){
            $id = $one->getId();
            $test = $newSale->getStocks()->filter(function($entity) use ($id){
                return $entity->getStock()->getId() == $id;
            });
            if($test->count() == 0){
                $newStock = new StockContent();
                $newStock->setStock($one);
                $newSale->addStock($newStock);
            }
        }

        return $content;
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

    public function preSave(\Symfony\Component\Form\Form $form, \Catalog\ContentBundle\Entity\Content $content)
    {
        foreach($content->getParameters() as $one){
            $content->removeParameter($one);
        }

        foreach($form->get('group_parameters')->getData() as $key => $group){
            foreach($form->get('group_parameters')->get($key)->getData() as $parameter){
                if(!$content->getParameters()->contains($parameter))
                    $content->addParameter($parameter);
            }
        }
    }

    protected function getQueryBuilder($pk)
    {
        return $this->getDoctrine()
            ->getManagerForClass('Catalog\ContentBundle\Entity\Content')
            ->getRepository('Catalog\ContentBundle\Entity\Content')
            ->createQueryBuilder('q')
            ->addSelect('m')
            ->where('q.id = :pk')
            ->leftJoin('q.meta', 'm')
            ->setParameter(':pk', $pk);
    }
}
