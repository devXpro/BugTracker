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
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;


class ProjectType extends AbstractType
{

    private $user;

    public function __construct(TokenStorageInterface $token)
    {

        $this->user =$token->getToken()->getUser();
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
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

    public function getName()
    {
        return 'bug_project';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'BugBundle\Entity\Project',

        ));
    }
}