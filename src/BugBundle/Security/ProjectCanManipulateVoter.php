<?php

namespace BugBundle\Security;

use BugBundle\Entity\Project;
use BugBundle\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;

class ProjectCanManipulateVoter extends BugAbstractVoter
{
    const CAN_MANIPULATE_ISSUE = 'can_manipulate_project';
    /** @var  EntityManager */
    private $em;

    /**
     * @param Registry $doctrine
     */
    public function __construct(Registry $doctrine)
    {
        $this->em = $doctrine->getManager();
    }

    /**
     * {@inheritdoc}
     */
    protected function getSupportedAttributes()
    {
        return array(self::CAN_MANIPULATE_ISSUE);
    }

    /**
     * {@inheritdoc}
     */
    protected function getSupportedClasses()
    {
        return array('BugBundle\Controller\ProjectController');
    }

    /**
     * {@inheritdoc}
     */
    public function decide(User $user, $obj, array $attributes)
    {

        if ($obj instanceof Project) {
            if ($this->em->getRepository('BugBundle:Project')->checkAccessProject($user, $obj)) {
                return VoterInterface::ACCESS_GRANTED;
            } else {
                return VoterInterface::ACCESS_DENIED;
            }
        }

        return VoterInterface::ACCESS_ABSTAIN;
    }
}
