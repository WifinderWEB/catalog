<?php

namespace Catalog\OrderBundle\Form;

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
            ->add('purchase_price')
            ->add('retail_price')
            ->add('VAT')
            ->add('weight')
            ->add('length')
            ->add('width')
            ->add('height')
            ->add('discount')
            ->add('barcode')
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
