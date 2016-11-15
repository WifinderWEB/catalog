<?php

namespace Catalog\VideoGalleryBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class VideoType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('url_video')
            ->add('image')
            ->add('sort')
            ->add('title')
            ->add('description')
            ->add('is_active')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Catalog\VideoGalleryBundle\Entity\Video'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'catalog_videogallerybundle_video';
    }
}
