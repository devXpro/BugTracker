<?php

namespace BugBundle\Form\Type;

use BugBundle\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class ProjectType extends AbstractType
{
    /** @var User */
    private $user;

    /**
     * @param TokenStorageInterface $token
     */
    public function __construct(TokenStorageInterface $token)
    {

        $this->user = $token->getToken()->getUser();
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('label', 'text')
            ->add('summary', 'textarea')
            ->add('code', 'text')
            ->add('members', 'bug_select_users')
            ->add('creator', 'bug_select_user', array('empty_data' => $this->user->getId()));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'bug_project';
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'BugBundle\Entity\Project',

            )
        );
    }
}
