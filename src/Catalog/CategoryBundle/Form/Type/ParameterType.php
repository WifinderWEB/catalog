<?php

namespace Catalog\CategoryBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ParameterType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', 'text', array(
                'translation_domain' => 'Admin'
            ))
            ->add('alias', 'text', array(
                'translation_domain' => 'Admin'
            ))
            ->add('is_active', 'checkbox', array(
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
            'data_class' => 'Catalog\CategoryBundle\Entity\Parameter',
            'validation_groups' =>array("Parameter"),
            'cascade_validation' => true
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'catalog_categorybundle_parameter';
    }
}