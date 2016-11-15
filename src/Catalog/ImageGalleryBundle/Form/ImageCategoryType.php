<?php

namespace Catalog\ImageGalleryBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ImageCategoryType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('sort')
            ->add('alias')
            ->add('title')
            ->add('description')
            ->add('is_active')
            ->add('join_content')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Catalog\ImageGalleryBundle\Entity\ImageCategory'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'catalog_imagegallerybundle_imagecategory';
    }
}
