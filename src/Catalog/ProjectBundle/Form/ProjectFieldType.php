<?php

namespace Catalog\ProjectBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Catalog\ProjectBundle\Entity\Repository\FieldRepository;

class ProjectFieldType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('is_active')
            ->add('field', 'entity', array(
                'required' => true,
                'empty_value' => ' ',
                'translation_domain' => 'Admin',
                'class' => 'CatalogProjectBundle:Field',
                'query_builder' => function(FieldRepository $er) {
                    return $er->getActiveFields();
                }
            ))
            ->add('title')
            ->add('alias')
            ->add('default_value')
            ->add('sort')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Catalog\ProjectBundle\Entity\ProjectField'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'catalog_projectbundle_projectfield';
    }
}
