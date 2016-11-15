<?php

namespace Catalog\CategoryBundle\Form\Type\Group;

use Admingenerated\CatalogCategoryBundle\Form\BaseGroupType\NewType as BaseNewType;
use Catalog\CategoryBundle\Form\Type\ParameterType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

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

        $builder->add('parameter', 'collection', array(
            'type' => new ParameterType(),
            'allow_add' => true,
            'allow_delete' => true
        ));
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Catalog\CategoryBundle\Entity\GroupParameters',
            'validation_groups' =>array("GroupParameters", "Parameter"),
            'cascade_validation' => true
        ));
    }

}
