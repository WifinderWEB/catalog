<?php

namespace Catalog\TagBundle\Form\Type\Tag;

use Symfony\Component\Form\FormBuilderInterface;
use Admingenerated\CatalogTagBundle\Form\BaseTagType\NewType as BaseNewType;

/**
 * NewType
 */
class NewType extends BaseNewType
{
    /**
     * (non-PHPdoc)
     * @see \Symfony\Component\Form\AbstractType::buildForm()
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        parent::buildForm($builder, $options);
        $builder->add('title', 'text', array(
            'required' => true,
            'label' => 'Title',
            'translation_domain' => 'Admin',
            'attr' => array('class' => 'title_box')
        ));
        $builder->add('alias', 'text', array(
            'required' => true,
            'label' => 'Alias',
            'translation_domain' => 'Admin',
            'attr' => array('class' => 'alias_box')
        ));
    }
}
