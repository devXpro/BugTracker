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
use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;

class IssueCanCreateChildrenVoter extends BugAbstractVoter
{
    const CAN_CREATE_CHILDREN_ISSUE = 'can_create_children_issue';
    /** @var EntityManager */
    private $em;

    public function __construct(Registry $doctrine)
    {
        $this->em = $doctrine->getManager();
    }

    protected function getSupportedAttributes()
    {
        return array(self::CAN_CREATE_CHILDREN_ISSUE);
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
            if ($obj->getType() != Issue::TYPE_STORY) {
                return VoterInterface::ACCESS_DENIED;
            }
        } else {
            return VoterInterface::ACCESS_ABSTAIN;
        }

        return VoterInterface::ACCESS_GRANTED;
    }
}
