<?php
/**
 * Created by PhpStorm.
 * User: roma
 * Date: 09.07.15
 * Time: 19:31
 */

namespace BugBundle\Security;

use BugBundle\Entity\Role;
use BugBundle\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class IssueCanCreateAnyVoter extends BugAbstractVoter
{
    const CREATE_ISSUE = 'can_create_any_issue';
    private $em;

    public function __construct(Registry $doctrine)
    {
        $this->em = $doctrine->getManager();
    }

    protected function getSupportedAttributes()
    {
        return array(self::CREATE_ISSUE);
    }

    protected function getSupportedClasses()
    {
        return array('BugBundle\Controller\IssueController');
    }

    /**
     * @param User $user
     * @param $obj
     * @param array $attributes
     * @return int
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
