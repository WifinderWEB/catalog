<?php

namespace Catalog\OrderBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Catalog\OrderBundle\Form\GoodsType;

class OrderType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('user')
            ->add('project')
            ->add('country')
            ->add('region')
            ->add('city')
            ->add('street')
            ->add('house')
            ->add('room')
            ->add('postcode')
            ->add('discount')
            ->add('itog')
            ->add('is_active')
            ->add('status')
            ->add('stock')
            ->add('created', 'datetime', array(
                'widget' => 'single_text',
                'format' => 'd.m.Y'
            ))
            ->add('updated')
            ->add('goods', 'collection', array(
                'allow_add' => true,
                'allow_delete' => true,
                'type' => new GoodsType()
            ))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'csrf_protection'   => false,
            'validation_groups' => array('Order'),
            'data_class' => 'Catalog\OrderBundle\Entity\Order'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'catalog_orderbundle_order';
    }
}
