<?php
/**
 * Created by PhpStorm.
 * User: roma
 * Date: 30.06.15
 * Time: 16:39
 */

namespace BugBundle\Form\Type;


use BugBundle\Entity\Issue;
use BugBundle\Entity\Role;
use BugBundle\Entity\User;
use BugBundle\Services\TransHelper;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;


class IssueType extends AbstractType
{

    private $trans;
    private $user;
    private $admin;

    public function __construct(ContainerInterface $interface)
    {

        $this->trans = $interface->get('bug.trans.helper');
        /** @var User user */
        $this->user = $interface->get('security.token_storage')->getToken()->getUser();
        $this->admin = $interface->get('security.authorization_checker')->isGranted(Role::ROLE_ADMIN);
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /** @var User $user */
        $user = $this->user;
        if ($this->admin)
            $builder
                ->add('project', 'entity', array('class' => 'BugBundle\Entity\Project'));
        else
            $builder
                ->add('project', 'entity', array(
                    'class' => 'BugBundle\Entity\Project',
                    'query_builder' => function (EntityRepository $er) use ($user) {
                        $qb = $er->createQueryBuilder('p')
                            ->leftJoin('p.members', 'members');
                        $qb->where($qb->expr()->orX(
                            $qb->expr()->in('members', ':user'),
                            $qb->expr()->eq('p.creator', ':user')

                        ))
                            ->setParameter('user', $user);
                        return $qb;
                    }
                ));
        $builder->add('summary', 'text')
            ->add('code', 'text')
            ->add('description', 'textarea')
            ->add('type', 'choice',
                array('choices' => array(
                    Issue::TYPE_BUG => $this->trans->transUp('bug'),
                    Issue::TYPE_STORY => $this->trans->transUp('story'),
                    Issue::TYPE_SUBTASK => $this->trans->transUp('subtask'),
                    Issue::TYPE_TASK => $this->trans->transUp('task'),
                )))
            ->add('priority', 'entity', array('class' => 'BugBundle\Entity\IssuePriority'))
            ->add('status', 'entity', array('class' => 'BugBundle\Entity\IssueStatus'))
            ->add('resolution', 'entity', array('class' => 'BugBundle\Entity\IssueResolution'))
            ->add('assignee', 'entity', array('class' => 'BugBundle\Entity\User'))
//            ->add('parentIssue', 'entity', array('class' => 'BugBundle\Entity\Issue','placeholder' => 'None','required'=>false))
            ->add('reporter', 'entity', array('class' => 'BugBundle\Entity\User', 'empty_data'  => $this->user->getId()))//            ->add('childrenIssues', 'entity', array('class' => 'BugBundle\Entity\Issue','multiple'=>true))

        ;
    }

    public function getName()
    {
        return 'bug_issue';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(

            'data_class' => 'BugBundle\Entity\Issue',
        ));




    }
}