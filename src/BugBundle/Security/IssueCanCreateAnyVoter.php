<?php

namespace BugBundle\Security;

use BugBundle\Entity\Role;
use BugBundle\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;

/**
 * @SuppressWarnings(PHPMD.ElseExpression)
 * Class IssueCanCreateAnyVoter
 * @package BugBundle\Security
 */
class IssueCanCreateAnyVoter extends BugAbstractVoter
{
    const CREATE_ISSUE = 'can_create_any_issue';
    /** @var EntityManager */
    private $em;

    public function __construct(Registry $doctrine)
    {
        $this->em = $doctrine->getManager();
    }

    /**
     * {@inheritdoc}
     */
    protected function getSupportedAttributes()
    {
        return array(self::CREATE_ISSUE);
    }

    /**
     * {@inheritdoc}
     */
    protected function getSupportedClasses()
    {
        return array('BugBundle\Controller\IssueController');
    }

    /**
     * {@inheritdoc}
     */
    public function decide(User $user, $obj, array $attributes)
    {
        if (in_array(Role::ROLE_ADMIN, $user->getRoles())) {
            if (count($this->em->getRepository('BugBundle:Project')->findAll()) < 1) {
                return VoterInterface::ACCESS_DENIED;
            }
        } else {
            if ($this->em->getRepository('BugBundle:Project')->countProjectsByUser($user) < 1) {
                return VoterInterface::ACCESS_DENIED;
            }
        }

        return VoterInterface::ACCESS_GRANTED;
    }
}
