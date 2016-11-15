<?php

namespace Catalog\ImageGalleryBundle\Controller\Image_category;

use Admingenerated\CatalogImageGalleryBundle\BaseImage_categoryController\EditController as BaseEditController;

/**
 * EditController
 */
class EditController extends BaseEditController {

    protected $originalImages = array();

    protected function saveObject(\Catalog\ImageGalleryBundle\Entity\ImageCategory $ImageCategory) {
        $em = $this->getDoctrine()->getManagerForClass('Catalog\ImageGalleryBundle\Entity\ImageCategory');
        foreach ($this->originalImages as $image) {
            $image->removeUpload();
            $em->remove($image);
        }
        $em->persist($ImageCategory);
        $em->flush();
    }

    /**
     * This method is here to make your life better, so overwrite  it
     *
     * @param \Catalog\ImageGalleryBundle\Entity\ImageCategory $ImageCategory your \Catalog\ImageGalleryBundle\Entity\ImageCategory object
     */
    public function preBindRequest(\Catalog\ImageGalleryBundle\Entity\ImageCategory $ImageCategory) {
        foreach ($ImageCategory->getImages() as $image) {
            $this->originalImages[] = $image;
        }
    }

    /**
     * This method is here to make your life better, so overwrite  it
     *
     * @param \Symfony\Component\Form\Form $form the valid form
     * @param \Catalog\ImageGalleryBundle\Entity\ImageCategory $ImageCategory your \Catalog\ImageGalleryBundle\Entity\ImageCategory object
     */
    public function preSave(\Symfony\Component\Form\Form $form, \Catalog\ImageGalleryBundle\Entity\ImageCategory $ImageCategory) {
        foreach ($ImageCategory->getImages() as $image) {
            foreach ($this->originalImages as $key => $toDel) {
                if ($toDel->getId() === $image->getId()) {
                    unset($this->originalImages[$key]);
                }
            }
        }
    }

}
