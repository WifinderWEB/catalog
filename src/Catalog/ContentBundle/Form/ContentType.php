<?php

namespace Catalog\ContentBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Catalog\ContentBundle\Form\ContentSaleType;

class ContentType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('alias')
            ->add('anons')
            ->add('image')
            ->add('content')
            ->add('big_image')
            ->add('is_active')
            ->add('image_gallery')
            ->add('video_gallery')
            ->add('catalogs')
            ->add('tags')
            ->add('sale', new ContentSaleType());
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Catalog\ContentBundle\Entity\Content'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'catalog_contentbundle_content';
    }
}
