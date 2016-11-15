<?php

namespace Catalog\FileGalleryBundle\Form\Type\File_category;

use Admingenerated\CatalogFileGalleryBundle\Form\BaseFile_categoryType\NewType as BaseNewType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * NewType
 */
class NewType extends BaseNewType
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
    }
}
