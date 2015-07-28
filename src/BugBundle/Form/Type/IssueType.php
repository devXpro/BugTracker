<?php

namespace BugBundle\Form\Type;

use BugBundle\Entity\Issue;
use BugBundle\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * @SuppressWarnings(PHPMD.ElseExpression)
 * Class IssueType
 * @package BugBundle\Form\Type
 */
class IssueType extends AbstractType
{

    /** @var User */
    private $user;

    /**
     * @param TokenStorageInterface $token
     */
    public function __construct(TokenStorageInterface $token)
    {
        /** @var User user */
        $this->user = $token->getToken()->getUser();
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /** @var User $user */

        $builder
            ->add('project', 'bug_select_project')
            ->add('summary', 'text')
            ->add('code', 'text')
            ->add('description', 'textarea');
        if ($options['parentIssue']) {
            $builder->add('type', 'bug_select_issue_type_subtask');
            $builder->add(
                'parentIssue',
                'bug_set_parent_issue',
                array(
                    'data' => $options['parentIssue'],
                    'empty_data' => $options['parentIssue']->getId(),
                )
            );
        } else {
            $builder->add('type', 'bug_select_issue_type');
        }
        $builder->add('priority', 'bug_select_issue_priority')
            ->add('status', 'bug_select_issue_status')
            ->add('resolution', 'bug_select_issue_resolution')
            ->add('assignee', 'bug_select_user')
            ->add('reporter', 'bug_select_user', array('empty_data' => $this->user->getId()));

    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'bug_issue';
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(

                'data_class' => 'BugBundle\Entity\Issue',
            )
        );

        $resolver->setRequired('parentIssue');
        $resolver->setAllowedValues(
            'parentIssue',
            function ($value) {
                if ($value instanceof Issue || $value == null) {
                    return true;
                } else {
                    return false;
                }
            }
        );
    }
}
