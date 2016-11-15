<?php

namespace Catalog\CatalogBundle\Controller\Catalog;

use Admingenerated\CatalogCatalogBundle\BaseCatalogController\ActionsController as BaseActionsController;

/**
 * ActionsController
 */
class ActionsController extends BaseActionsController
{
    protected function executeObjectDelete(\Catalog\CatalogBundle\Entity\Catalog $Catalog)
    {
        $em = $this->getDoctrine()->getManagerForClass('Catalog\CatalogBundle\Entity\Catalog');
        $repo = $em->getRepository('Catalog\CatalogBundle\Entity\Catalog');
        $repo->removeFromTree($Catalog);
        if($repo->verify() === true)
            $repo->recover();
        $em->flush();
        $em->clear();
    }
    
    public function refreshContentAction($id){
        $content = $this->getDoctrine()->getRepository('CatalogContentBundle:Content')->getContentForSelectList()->getQuery()
                ->getResult();
        
        return $this->render('CatalogCatalogBundle:CatalogActions:_contentSelectList.html.twig', array(
            'content' => $content,
            'id' => $id
        ));
    }
}
