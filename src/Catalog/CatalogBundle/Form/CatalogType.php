<?php

namespace Catalog\CatalogBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CatalogType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('alias')
            ->add('title')
            ->add('description')
            ->add('is_active')
            ->add('lft')
            ->add('rgt')
            ->add('root')
            ->add('level')
            ->add('parent')
            ->add('contents')
            ->add('meta')
            ->add('sale')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Catalog\CatalogBundle\Entity\Catalog'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'catalog_catalogbundle_catalog';
    }
}
