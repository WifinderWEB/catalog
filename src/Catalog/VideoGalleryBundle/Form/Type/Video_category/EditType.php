<?php

namespace Catalog\VideoGalleryBundle\Form\Type\Video_category;

use Symfony\Component\Form\FormBuilderInterface;
use Admingenerated\CatalogVideoGalleryBundle\Form\BaseVideo_categoryType\EditType as BaseEditType;
use Catalog\VideoGalleryBundle\Form\VideoType;

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
        $builder->add('videos', 'collection', array(
            'type' => new VideoType(),
            'allow_add' => true,
            'allow_delete' => true,
            'by_reference' => false,
            'translation_domain' => 'Admin'
        ));
    }
}
