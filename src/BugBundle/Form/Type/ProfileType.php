<?php

namespace BugBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class ProfileType
 * @package BugBundle\Form\Type
 */
class ProfileType extends AbstractType
{

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', 'email')
            ->add('ava', 'file', array('required' => false))
            ->add('fullName', 'text', array('required' => false))
            ->add(
                'plainPassword',
                'repeated',
                array(
                    'mapped' => false,
                    'type' => 'password',
                    'invalid_message' => 'The password fields must match.',
                    'options' => array('attr' => array('class' => 'password-field')),
                    'required' => true,
                    'first_options' => array('label' => 'Password'),
                    'second_options' => array('label' => 'Repeat Password'),
                )
            );

    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'bug_user_profile';
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'BugBundle\Entity\User',
            )
        );
    }
}
