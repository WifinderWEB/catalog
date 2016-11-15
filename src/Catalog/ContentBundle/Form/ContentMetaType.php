<?php

namespace Catalog\ContentBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ContentMetaType extends AbstractType
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
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Catalog\ContentBundle\Entity\ContentMeta'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'catalog_contentbundle_contentmeta';
    }
}
