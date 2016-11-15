<?php

namespace Catalog\CatalogBundle\Controller\Catalog;

use Admingenerated\CatalogCatalogBundle\BaseCatalogController\EditController as BaseEditController;
use Catalog\CatalogBundle\Entity\CatalogParameter;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * EditController
 */
class EditController extends BaseEditController {

    public function updateAction($pk) {
        $Catalog = $this->getObject($pk);

        if (!$Catalog) {
            throw new NotFoundHttpException("The Catalog\CatalogBundle\Entity\Catalog with id $pk can't be found");
        }

        $this->preBindRequest($Catalog);
        $form = $this->createForm($this->getEditType(), $Catalog, $this->getFormOptions($Catalog));
        $form->bind($this->get('request'));

        if ($form->isValid()) {
            try {

                $this->preSave($form, $Catalog);
                $this->saveObject($Catalog);
                $this->postSave($form, $Catalog);

                $this->get('session')->getFlashBag()->add('success', $this->get('translator')->trans("action.object.edit.success", array(), 'Admingenerator'));

                if ($this->get('request')->request->has('save-and-add')){
                    if($Catalog->getParent())
                        return new RedirectResponse($this->generateUrl("Catalog_CatalogBundle_Catalog_new", array('parent_id' => $Catalog->getParent()->getId())));
                    else
                        return new RedirectResponse($this->generateUrl("Catalog_CatalogBundle_Catalog_new"));
                }
                if ($this->get('request')->request->has('save-and-list'))
                    return new RedirectResponse($this->generateUrl("Catalog_CatalogBundle_Catalog_list"));
                else
                    return new RedirectResponse($this->generateUrl("Catalog_CatalogBundle_Catalog_edit", array('pk' => $pk)));
            } catch (\Exception $e) {
                $this->get('session')->getFlashBag()->add('error', $this->get('translator')->trans("action.object.edit.error", array(), 'Admingenerator'));
                $this->onException($e, $form, $Catalog);
            }
        } else {
            $this->get('session')->getFlashBag()->add('error', $this->get('translator')->trans("action.object.edit.error", array(), 'Admingenerator'));
        }

        return $this->render('CatalogCatalogBundle:CatalogEdit:index.html.twig', $this->getAdditionalRenderParameters($Catalog) + array(
                    "Catalog" => $Catalog,
                    "form" => $form->createView(),
        ));
    }

//    public function preSave(\Symfony\Component\Form\Form $form, \Catalog\CatalogBundle\Entity\Catalog $catalog)
//    {
//        foreach($catalog->getParameters() as $one){
//            $catalog->removeParameter($one);
//        }
////
////        var_dump($form->get('group_parameters')->getData());
//        foreach($form->get('group_parameters')->getData() as $key => $group){
//
//            foreach($form->get('group_parameters')->get($key)->getData() as $parameter){
//                if(!$catalog->getParameters()->contains($parameter))
//                        $catalog->addParameter($parameter);
//            }
////            if($group){
////                foreach($group as $parameter){
////                    var_dump(' = ' . $parameter->getId().' - '. $catalog->getParameters()->contains($parameter));
////                    if(!$catalog->getParameters()->contains($parameter))
////                        $catalog->addParameter($parameter);
////                }
////            }
//        }
//
////        var_dump('4 - ' . $catalog->getParameters()->count());
////        exit;
//    }

//    public function preBindRequest(\Catalog\CatalogBundle\Entity\Catalog $catalog) {
//        var_dump('1 - ' . $catalog->getParameters()->count());
//
//        foreach($catalog->getParameters() as $one){
//            $catalog->removeParameter($one);
//        }
//        var_dump('2 -  ' . $catalog->getParameters()->count());
//
//        foreach($catalog->getGroupParameters() as $group){
//            if($group){
//                foreach($group as $parameter){
//                    $catalog->addParameter($parameter);
//                }
//            }
//        }
//    }
}
