<?php

namespace Catalog\ProjectBundle\Controller\Project;

use Admingenerated\CatalogProjectBundle\BaseProjectController\EditController as BaseEditController;

/**
 * EditController
 */
class EditController extends BaseEditController
{
    protected $originalFields = array();

    protected function saveObject(\Catalog\ProjectBundle\Entity\Project $Project) {
        $em = $this->getDoctrine()->getManagerForClass('Catalog\ProjectBundle\Entity\Project');
        foreach ($this->originalFields as $field) {
            $em->remove($field);
        }
        $em->persist($Project);
        $em->flush();
    }

    /**
     * This method is here to make your life better, so overwrite  it
     *
     * @param \Catalog\ProjectBundle\Entity\Project $Project your \Catalog\ProjectBundle\Entity\Project object
     */
    public function preBindRequest(\Catalog\ProjectBundle\Entity\Project $Project) {
        foreach ($Project->getFields() as $field) {
            $this->originalFields[] = $field;
        }
    }

    /**
     * This method is here to make your life better, so overwrite  it
     *
     * @param \Symfony\Component\Form\Form $form the valid form
     * @param \Catalog\ProjectBundle\Entity\Project $Project your \Catalog\ProjectBundle\Entity\Project object
     */
    public function preSave(\Symfony\Component\Form\Form $form, \Catalog\ProjectBundle\Entity\Project $Project) {
        foreach ($Project->getFields() as $field) {
            foreach ($this->originalFields as $key => $toDel) {
                if ($toDel->getId() === $field->getId()) {
                    unset($this->originalFields[$key]);
                }
            }
        }
    }
}
