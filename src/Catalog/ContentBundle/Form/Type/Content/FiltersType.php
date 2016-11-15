<?php

namespace Catalog\ContentBundle\Form\Type\Content;

use Symfony\Component\Form\FormBuilderInterface;
use Admingenerated\CatalogContentBundle\Form\BaseContentType\FiltersType as BaseFiltersType;
use Catalog\CatalogBundle\Entity\Repository\CatalogRepository;
use Catalog\TagBundle\Entity\Repository\TagRepository;

/**
 * FiltersType
 */
class FiltersType extends BaseFiltersType
{
    /**
     * (non-PHPdoc)
     * @see \Symfony\Component\Form\AbstractType::buildForm()
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        parent::buildForm($builder, $options);
        
        $builder->add('catalogs', 'entity', array(
            'required' => false,
            'em' => 'default',
            'translation_domain' => 'Admin',
            'class' => 'CatalogCatalogBundle:Catalog',
            'query_builder' => function(CatalogRepository $er){
                return $er->getListCategory();
            },
        ));
        $builder->add('tags', 'entity', array(
            'required' => false,
            'em' => 'default',
            'class' => 'CatalogTagBundle:Tag',
            'translation_domain' => 'Admin',
            'query_builder' => function(TagRepository $er){
                return $er->getListTags();
            },
        ));
    }
}
