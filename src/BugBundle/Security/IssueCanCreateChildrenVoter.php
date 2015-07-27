<?php

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

    /**
     * {@inheritdoc}
     */
    protected function getSupportedAttributes()
    {
        return array(self::CAN_CREATE_CHILDREN_ISSUE);
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
