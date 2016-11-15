<?php

namespace Catalog\CatalogBundle\Controller\Catalog;

use Admingenerated\CatalogCatalogBundle\BaseCatalogController\NewController as BaseNewController;
use Catalog\CatalogBundle\Entity\CatalogProject;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * NewController
 */
class NewController extends BaseNewController {

    protected function saveObject(\Catalog\CatalogBundle\Entity\Catalog $Catalog) {
        if ($this->get('session')->get('project_id')) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($Catalog);
            $em->flush();

            if ($Catalog->isRoot()) {
                $catalogProject = new CatalogProject($Catalog, $em->getRepository('CatalogProjectBundle:Project')->find($this->get('session')->get('project_id')));
                $em->persist($catalogProject);

                $em->flush();
            }
        }
    }

    protected function getNewObject() {
        $catalog = new \Catalog\CatalogBundle\Entity\Catalog;
        if($this->getRequest()->query->get('parent_id'))
            $catalog->setParent($this->getDoctrine()->getManager()->getRepository('CatalogCatalogBundle:Catalog')->find($this->getRequest()->query->get('parent_id')));
        $meta = new \Catalog\CatalogBundle\Entity\CatalogMeta;
        $meta->setInBreadcrumbs(true);
        $meta->setInRobots(true);
        $meta->setInSiteMap(true);
        $catalog->setMeta($meta);
        return $catalog;
    }

    public function createAction() {
        $Catalog = $this->getNewObject();

        $this->preBindRequest($Catalog);
        $form = $this->createForm($this->getNewType(), $Catalog, $this->getFormOptions($Catalog));
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
                    return new RedirectResponse($this->generateUrl("Catalog_CatalogBundle_Catalog_edit", array('pk' => $Catalog->getId())));
            } catch (\Exception $e) {
                $this->get('session')->getFlashBag()->add('error', $this->get('translator')->trans("action.object.edit.error", array(), 'Admingenerator'));
                $this->onException($e, $form, $Catalog);
            }
        } else {
            $this->get('session')->getFlashBag()->add('error', $this->get('translator')->trans("action.object.edit.error", array(), 'Admingenerator'));
        }

        return $this->render('CatalogCatalogBundle:CatalogNew:index.html.twig', $this->getAdditionalRenderParameters($Catalog) + array(
                    "Catalog" => $Catalog,
                    "form" => $form->createView(),
        ));
    }

}
