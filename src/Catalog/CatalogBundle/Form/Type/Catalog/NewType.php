<?php

namespace Catalog\CatalogBundle\Form\Type\Catalog;

use Catalog\CategoryBundle\Entity\Repository\CategoryRepository;
use Symfony\Component\Form\FormBuilderInterface;
use Admingenerated\CatalogCatalogBundle\Form\BaseCatalogType\NewType as BaseNewType;
use Catalog\CatalogBundle\Form\CatalogMetaType;
use Catalog\CatalogBundle\Entity\Repository\CatalogRepository;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Catalog\ContentBundle\Entity\Repository\ContentRepository;

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

        $builder->add('meta', new CatalogMetaType());

//        $builder->add('related', 'entity', array(
//            'required' => false,
//            'multiple' => true,
//            'em' => 'default',
//            'translation_domain' => 'Admin',
//            'class' => 'CatalogCatalogBundle:Catalog',
//            'query_builder' => function(CatalogRepository $er) {
//                return $er->getListCategory();
//            },
//            'attr' => array('class' => 'multiselect')
//        ));
        $builder->add('parent', 'entity', array(
            'multiple' => false,
            'em' => 'default',
            'class' => 'CatalogCatalogBundle:Catalog',
            'required' => false,
            'label' => 'Parent',
            'translation_domain' => 'Admin',
            'query_builder' => function(CatalogRepository $er) {
                return $er->getListCategory();
            },
        ));
        $builder->add('content', 'entity', array(
            'class' => 'CatalogContentBundle:Content',
            'required' => false,
            'query_builder' => function(ContentRepository $er) {
                return $er->getContentForSelectList();
            },
        ));
//        $builder->add('category', 'entity', array(
//            'class' => 'CatalogCategoryBundle:Category',
//            'required' => false,
//            'query_builder' => function(CategoryRepository $er) {
//                return $er->getListCategory();
//            },
//        ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'validation_groups' => array('Catalog'),
            'data_class' => 'Catalog\CatalogBundle\Entity\Catalog'
        ));
    }

}
