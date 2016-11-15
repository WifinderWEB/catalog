<?php

namespace Catalog\ContentBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Catalog\StockBundle\Form\StockContentType;

class ContentSaleType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('purchase_price', 'text', array(
                'required' => false,
                'translation_domain' => 'Admin'
            ))
            ->add('retail_price', 'text', array(
                'required' => false,
                'translation_domain' => 'Admin'
            ))
            ->add('VAT', 'checkbox', array(
                'required' => false,
                'translation_domain' => 'Admin'
            ))
            ->add('weight', 'text', array(
                'required' => false,
                'translation_domain' => 'Admin'
            ))
            ->add('length', 'text', array(
                'required' => false,
                'translation_domain' => 'Admin'
            ))
            ->add('width', 'text', array(
                'required' => false,
                'translation_domain' => 'Admin'
            ))
            ->add('height', 'text', array(
                'required' => false,
                'translation_domain' => 'Admin'
            ))
            ->add('discount', 'text', array(
                'required' => false,
                'translation_domain' => 'Admin'
            ))
            ->add('barcode', 'text', array(
                'required' => false,
                'translation_domain' => 'Admin'
            ))
        ;
        $builder->add('stocks', 'collection', array('type' => new StockContentType()));
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Catalog\ContentBundle\Entity\ContentSale'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'catalog_contentbundle_contentsale';
    }
}
