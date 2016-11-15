<?php

namespace Catalog\CategoryBundle\Form\Type\Category;

use Admingenerated\CatalogCategoryBundle\Form\BaseCategoryType\EditType as BaseEditType;
use Catalog\CategoryBundle\Entity\Repository\CategoryRepository;
use Symfony\Component\Form\FormBuilderInterface;

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

        $builder->add('parent', 'entity', array(
            'multiple' => false,
            'em' => 'default',
            'class' => 'CatalogCategoryBundle:Category',
            'required' => false,
            'label' => 'Parent',
            'translation_domain' => 'Admin',
            'query_builder' => function(CategoryRepository $er){
                return $er->getListCategory();
            },
        ));
        $builder->add('groups', 'entity', array(
            'required' => false,
            'multiple' => true,
            'em' => 'default',
            'translation_domain' => 'Admin',
            'class' => 'CatalogCategoryBundle:GroupParameters',
//            'query_builder' => function(Repository $er) {
//                return $er->getListCategory();
//            },
            'attr' => array('class' => 'multiselect')
        ));
        if($builder->getData()->getShowEditorAnons()){
            $builder->add('anons', 'ckeditor', array(
                'required' => false,
                'label' => 'Content',
                'translation_domain' => 'Admin'
            ));
        }
        else{
            $builder->add('anons', 'textarea', array(
                'required' => false,
                'label' => 'Content',
                'translation_domain' => 'Admin'
            ));
        }
        $builder->add('show_editor_anons', 'checkbox', array(
            'required' => false,
            'label' => 'Show editor content',
            'translation_domain' => 'Admin'
        ));
    }
}
