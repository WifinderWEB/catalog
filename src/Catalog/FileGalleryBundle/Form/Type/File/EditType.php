<?php

namespace Catalog\FileGalleryBundle\Form\Type\File;

use Admingenerated\CatalogFileGalleryBundle\Form\BaseFileType\EditType as BaseEditType;
use Symfony\Component\Form\FormBuilderInterface;
use Catalog\FileGalleryBundle\Entity\Repository\FileCategoryRepository;

/**
 * EditType
 */
class EditType extends BaseEditType
{
    /**
     * (non-PHPdoc)
     * @see \Symfony\Component\Form\AbstractType::buildForm()
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        parent::buildForm($builder, $options);
        $builder->add('description', 'ckeditor', array(
            'required' => false,
            'label' => 'Anons',
            'translation_domain' => 'Admin',
        ));
        $builder->add('file', 'file', array(
            'required' => false
        ));
        $builder->add('category', 'entity', array(
            'required' => false,
            'em' => 'default',
            'translation_domain' => 'Admin',
            'class' => 'CatalogFileGalleryBundle:FileCategory',
            'query_builder' => function(FileCategoryRepository $er){
                return $er->getListCategory();
            },
        ));
    }
}
