<?php
/**
 * Created by PhpStorm.
 * User: roma
 * Date: 30.06.15
 * Time: 16:39
 */

namespace BugBundle\Form\Type;


use BugBundle\Entity\Role;
use BugBundle\Entity\User;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class ProjectSelectType extends AbstractType
{
    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    /**
     * @var AuthorizationCheckerInterface
     */
    private $authorizationChecker;


    /**
     * @param TokenStorageInterface         $tokenStorage
     * @param AuthorizationCheckerInterface $authorizationChecker
     */
    public function __construct(TokenStorageInterface $tokenStorage, AuthorizationCheckerInterface $authorizationChecker)
    {
        $this->tokenStorage = $tokenStorage;
        $this->authorizationChecker = $authorizationChecker;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

    }

    public function getName()
    {
        return 'bug_select_project';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $params = array('class' => 'BugBundle\Entity\Project');
        $user = $this->getUser();
        if ($this->isAdmin()) {
            $params['query_builder'] = function (EntityRepository $er) use ($user) {
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

    public function getParent()
    {
        return 'entity';
    }

    /**
     * @return User
     */
    private function getUser()
    {
        return $this->tokenStorage->getToken()->getUser();
    }

    /**
     * @return bool
     */
    private function isAdmin()
    {
        return $this->authorizationChecker->isGranted(Role::ROLE_ADMIN);
    }
}