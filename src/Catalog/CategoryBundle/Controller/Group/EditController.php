<?php

namespace Catalog\CategoryBundle\Controller\Group;

use Admingenerated\CatalogCategoryBundle\BaseGroupController\EditController as BaseEditController;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * EditController
 */
class EditController extends BaseEditController
{
    protected $originalTags;

    public function preBindRequest(\Catalog\CategoryBundle\Entity\GroupParameters $GroupParameters)
    {
        $this->originalTags = new ArrayCollection();

        foreach ($GroupParameters->getParameter() as $param) {
            $this->originalTags->add($param);
        }
    }

    public function preSave(\Symfony\Component\Form\Form $form, \Catalog\CategoryBundle\Entity\GroupParameters $GroupParameters)
    {
        $em = $this->getDoctrine()->getManager();

        foreach ($this->originalTags as $param) {
            if (false === $GroupParameters->getParameter()->contains($param)) {
                $em->remove($param);
            }
        }

        foreach ($GroupParameters->getParameter() as $item) {
            $item->setGroup($GroupParameters);
        }
    }
}
