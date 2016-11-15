<?php

namespace Catalog\FileGalleryBundle\Form\Type\File;

use Admingenerated\CatalogFileGalleryBundle\Form\BaseFileType\FiltersType as BaseFiltersType;
use Catalog\FileGalleryBundle\Entity\Repository\FileCategoryRepository;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * FiltersType
 */
class FiltersType extends BaseFiltersType
{
    /**
     * (non-PHPdoc)
     * @see \Symfony\Component\Form\AbstractType::buildForm()
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        parent::buildForm($builder, $options);
        
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
