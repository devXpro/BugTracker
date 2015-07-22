<?php
/**
 * Created by PhpStorm.
 * User: roma
 * Date: 30.06.15
 * Time: 16:39
 */

namespace BugBundle\Form\Type;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProfileType extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', 'email')
            ->add('ava', 'file', array('required' => false))
            ->add('fullName', 'text', array('required' => false))
            ->add(
                'password',
                'repeated',
                array(
                    'type' => 'password',
                    'invalid_message' => 'The password fields must match.',
                    'options' => array('attr' => array('class' => 'password-field')),
                    'required' => true,
                    'first_options' => array('label' => 'Password'),
                    'second_options' => array('label' => 'Repeat Password'),
                )
            );

    }

    public function getName()
    {
        return 'bug_user_profile';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'BugBundle\Entity\User',
            )
        );
    }
}