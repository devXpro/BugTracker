<?php

namespace BugBundle\Form\Type;

use BugBundle\Entity\ProjectRepository;
use BugBundle\Entity\Role;
use BugBundle\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class ProjectSelectType extends AbstractType
{
    /** @var User */
    private $user;
    /** @var  boolean */
    private $admin;

    /**
     * @param TokenStorageInterface $token
     * @param AuthorizationCheckerInterface $authorizationChecker
     */
    public function __construct(TokenStorageInterface $token, AuthorizationCheckerInterface $authorizationChecker)
    {
        $this->user = $token->getToken()->getUser();
        $this->admin = $authorizationChecker->isGranted(Role::ROLE_ADMIN);
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'bug_select_project';
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $params = array('class' => 'BugBundle\Entity\Project');
        $user = $this->user;
        if (!$this->admin) {
            $params['query_builder'] = function (ProjectRepository $er) use ($user) {
                $qb = $er->createQueryBuilder('p')
                    ->leftJoin('p.members', 'members');
                $qb->where(
                    $qb->expr()->orX(
                        $qb->expr()->in('members', ':user'),
                        $qb->expr()->eq('p.creator', ':user')
                    )
                )
                    ->setParameter('user', $user);

                return $qb;
            };
        }

        $resolver->setDefaults($params);
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return 'entity';
    }
}
