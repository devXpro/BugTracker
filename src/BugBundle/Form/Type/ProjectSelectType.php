<?php
/**
 * Created by PhpStorm.
 * User: roma
 * Date: 30.06.15
 * Time: 16:39
 */

namespace BugBundle\Form\Type;


use BugBundle\Entity\ProjectRepository;
use BugBundle\Entity\Role;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class ProjectSelectType extends AbstractType
{

    private $user;

    private $admin;

    public function __construct(TokenStorageInterface $token, AuthorizationCheckerInterface $authorizationChecker)
    {
        $this->user = $token->getToken()->getUser();
        $this->admin = $authorizationChecker->isGranted(Role::ROLE_ADMIN);
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

    public function getParent()
    {
        return 'entity';
    }
}