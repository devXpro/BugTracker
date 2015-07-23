<?php
/**
 * Created by PhpStorm.
 * User: roma
 * Date: 09.07.15
 * Time: 19:31
 */

namespace BugBundle\Security;

use BugBundle\Entity\Issue;
use BugBundle\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class IssueCanManipulateVoter extends BugAbstractVoter
{
    const CAN_MANIPULATE_ISSUE = 'can_manipulate_issue';
    private $em;

    public function __construct(Registry $doctrine)
    {
        $this->em = $doctrine->getManager();
    }

    protected function getSupportedAttributes()
    {
        return array(self::CAN_MANIPULATE_ISSUE);
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

        if ($obj instanceof Issue) {
            if ($this->em->getRepository('BugBundle:Issue')->checkIssueUserAccess($user, $obj)) {
                return VoterInterface::ACCESS_GRANTED;
            } else {
                return VoterInterface::ACCESS_DENIED;
            }
        }

        return VoterInterface::ACCESS_ABSTAIN;
    }
}
