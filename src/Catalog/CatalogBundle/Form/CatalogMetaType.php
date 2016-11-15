<?php

namespace Catalog\CatalogBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CatalogMetaType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('meta_title', 'text', array(
                'label' => 'Title',
                'required' => false,
                'translation_domain' => 'Admin'
            ))
            ->add('meta_keywords', 'text', array(
                'required' => false,
                'translation_domain' => 'Admin'
            ))
            ->add('meta_description', 'textarea', array(
                'required' => false,
                'translation_domain' => 'Admin'
            ))
            ->add('more_scripts', 'textarea', array(
                'required' => false,
                'translation_domain' => 'Admin'
            ))
            ->add('in_site_map', 'checkbox', array(
                'required' => false,
                'translation_domain' => 'Admin'
            ))
            ->add('in_robots', 'checkbox', array(
                'required' => false,
                'translation_domain' => 'Admin'
            ))
            ->add('in_breadcrumbs', 'checkbox', array(
                'required' => false,
                'translation_domain' => 'Admin'
            ))
            ->add('in_menu', 'checkbox', array(
                'translation_domain' => 'Admin'
            ))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Catalog\CatalogBundle\Entity\CatalogMeta'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'catalog_catalogbundle_catalogmeta';
    }
}

