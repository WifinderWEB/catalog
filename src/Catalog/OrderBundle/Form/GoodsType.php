<?php

namespace Catalog\OrderBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class GoodsType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('id')
            ->add('goods_id')
            ->add('title')
            ->add('alias')
            ->add('article')
            ->add('price')
            ->add('discount')
            ->add('count')
            ->add('image_path')
            ->add('title_image')
            ->add('alt_image')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'validation_groups' => array('Order'),
            'data_class' => 'Catalog\OrderBundle\Entity\Goods'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'catalog_orderbundle_goods';
    }
}
