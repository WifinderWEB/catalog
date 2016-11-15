<?php

namespace Catalog\ImageGalleryBundle\Form\Type\Image_category;

use Symfony\Component\Form\FormBuilderInterface;
use Admingenerated\CatalogImageGalleryBundle\Form\BaseImage_categoryType\NewType as BaseNewType;
use Catalog\ImageGalleryBundle\Form\ImageType;

/**
 * NewType
 */
class NewType extends BaseNewType {

    /**
     * (non-PHPdoc)
     * @see \Symfony\Component\Form\AbstractType::buildForm()
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        parent::buildForm($builder, $options);

        $builder->add('title', 'text', array(
            'required' => true,
            'label' => 'Title',
            'translation_domain' => 'Admin',
            'attr' => array('class' => 'title_box')
        ));
        $builder->add('alias', 'text', array(
            'required' => true,
            'label' => 'Alias',
            'translation_domain' => 'Admin',
            'attr' => array('class' => 'alias_box')
        ));
        $builder->add('description', 'ckeditor', array(
            'required' => false,
            'label' => 'Anons',
            'translation_domain' => 'Admin',
        ));
        $builder->add('images', 'collection', array(
            'type' => new ImageType(),
            'allow_add' => true,
            'allow_delete' => true,
            'by_reference' => false,
            'translation_domain' => 'Admin'
        ));
    }

}
