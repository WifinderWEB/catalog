<?php

namespace Catalog\UserBundle\Form\Type\User;

use Admingenerated\CatalogUserBundle\Form\BaseUserType\NewType as BaseNewType;
use Symfony\Component\Form\FormBuilderInterface;
use Catalog\UserBundle\Entity\User;

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

        $builder->add('email', 'email', array('label' => 'form.email', 'translation_domain' => 'FOSUserBundle'));
        $builder->add('username', null, array('label' => 'form.username', 'translation_domain' => 'FOSUserBundle'));
        $builder->add('plainPassword', 'repeated', array(
                'type' => 'password',
                'options' => array('translation_domain' => 'FOSUserBundle'),
                'first_options' => array('label' => 'form.password'),
                'second_options' => array('label' => 'form.password_confirmation'),
                'invalid_message' => 'fos_user.password.mismatch',
            ));
        $builder->add('projects', 'entity', array(
            'required' => false,
            'multiple' => true,
            'translation_domain' => 'Admin',
            'class' => 'CatalogProjectBundle:Project',
            'attr' => array('class' => 'multiselect')
        ));
    }
}
