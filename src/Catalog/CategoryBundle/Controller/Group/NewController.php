<?php

namespace Catalog\CategoryBundle\Controller\Group;

use Admingenerated\CatalogCategoryBundle\BaseGroupController\NewController as BaseNewController;

/**
 * NewController
 */
class NewController extends BaseNewController
{
    public function preSave(\Symfony\Component\Form\Form $form, \Catalog\CategoryBundle\Entity\GroupParameters $GroupParameters)
    {
        foreach ($GroupParameters->getParameter() as $item) {
            $item->setGroup($GroupParameters);
        }
    }
}
