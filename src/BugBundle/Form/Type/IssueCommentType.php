<?php
/**
 * Created by PhpStorm.
 * User: roma
 * Date: 30.06.15
 * Time: 16:39
 */

namespace BugBundle\Form\Type;


use BugBundle\Entity\Issue;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class IssueCommentType extends AbstractType
{


    private $user;


    public function __construct(ContainerInterface $interface)
    {


        /** @var User user */
        $this->user = $interface->get('security.token_storage')->getToken()->getUser();

    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('issue', 'entity', array('class' => 'BugBundle\Entity\Issue', 'empty_data' => $options['issue']->getId()))
            ->add('body', 'textarea')
            ->add('author', 'entity', array('class' => 'BugBundle\Entity\User', 'data' => $this->user, 'empty_data' => $this->user->getId()));


    }

    public function getName()
    {
        return 'bug_issue_comment';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'BugBundle\Entity\IssueComment',
        ));

        $resolver->setRequired('issue');
        $resolver->setAllowedValues('issue', function ($value) {
            if ($value instanceof Issue) return true; else return false;
        });

    }
}