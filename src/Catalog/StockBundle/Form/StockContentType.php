<?php

namespace Catalog\StockBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class StockContentType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('quantity', 'text', array(
                'required' => false,
                'translation_domain' => 'Admin'
            ))
            ->add('reserved', 'text', array(
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
            'data_class' => 'Catalog\StockBundle\Entity\StockContent'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'catalog_stockbundle_stockcontent';
    }
}