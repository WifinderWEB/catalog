<?php

namespace Catalog\ProjectBundle\Form\Type\Project;

use Admingenerated\CatalogProjectBundle\Form\BaseProjectType\EditType as BaseEditType;
use Symfony\Component\Form\FormBuilderInterface;
use Catalog\ProjectBundle\Form\ProjectFieldType;

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
        $builder->add('image', 'file', array(
            'required' => false
        ));
        $builder->add('delete_image', 'checkbox', array(
            'label'     => 'Delete image?',
            'required'  => false,
            'translation_domain' => 'Admin',
        ));
        $builder->add('description', 'ckeditor', array(
            'required' => false,
            'label' => 'Anons',
            'translation_domain' => 'Admin',
        ));
        $builder->add('fields', 'collection', array(
            'type' => new ProjectFieldType(),
            'allow_add' => true,
            'allow_delete' => true,
            'by_reference' => false,
            'translation_domain' => 'Admin'
        ));
    }

}
