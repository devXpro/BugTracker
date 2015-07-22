<?php
/**
 * Created by PhpStorm.
 * User: roma
 * Date: 09.07.15
 * Time: 19:31
 */

namespace BugBundle\Security;


use BugBundle\Entity\Issue;
use BugBundle\Entity\Role;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\User\User;
use Symfony\Component\Security\Core\User\UserInterface;

class IssueVoter extends BugAbstractVoter
{
    const CREATE_ISSUE = 'can_create_any_issue';
    const CAN_CREATE_CHILDREN_ISSUE = 'can_create_children_issue';
    private $em;

    public function __construct(Registry $doctrine)
    {
        $this->em = $doctrine->getManager();
    }

    protected function getSupportedAttributes()
    {
        return array(self::CREATE_ISSUE, self::CAN_CREATE_CHILDREN_ISSUE);
    }

    protected function getSupportedClasses()
    {
        return array('BugBundle\Controller\IssueController');
    }

    public function vote(TokenInterface $token, $obj, array $attributes)
    {
        /** @var User $user */
        $user = $token->getUser();
        if (!$this->checkSupportedInAttributes($attributes)) {
            return false;
        }
        if (!$user instanceof UserInterface) {
            return false;
        }

        if (in_array(self::CREATE_ISSUE, $attributes)) {
            if (!in_array(Role::ROLE_ADMIN, $user->getRoles()) &&
                count($this->em->getRepository('BugBundle:Project')->getProjectsByUser($user)) < 1
            ) {
                return false;
            }
        }
        if (in_array(self::CAN_CREATE_CHILDREN_ISSUE, $attributes)) {
            if ($obj) {
                if (!($obj instanceof Issue)) {
                    $obj = $this->em->getRepository('BugBundle:Issue')->find($obj);
                }
                if ($obj->getType() != Issue::TYPE_STORY) {
                    return false;
                }
            }

        }

        return true;
    }
}