<?php
/**
 * Created by PhpStorm.
 * User: roma
 * Date: 30.06.15
 * Time: 16:39
 */

namespace BugBundle\Form\Type;


use BugBundle\Entity\Issue;
use BugBundle\Entity\User;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class IssueCommentType extends AbstractType
{


    private $user;


    public function __construct(TokenStorageInterface $token)
    {

        /** @var User user */
        $this->user = $token->getToken()->getUser();

    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('issue', 'bug_select_issue', array('empty_data' => $options['issue']->getId()))
            ->add('body', 'textarea')
            ->add('author', 'bug_select_user', array('data' => $this->user, 'empty_data' => $this->user->getId()));


    }

    public function getName()
    {
        return 'bug_issue_comment';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'BugBundle\Entity\IssueComment',
            )
        );

        $resolver->setRequired('issue');
        $resolver->setAllowedValues(
            'issue',
            function ($value) {
                if ($value instanceof Issue) {
                    return true;
                } else {
                    return false;
                }
            }
        );

    }
}