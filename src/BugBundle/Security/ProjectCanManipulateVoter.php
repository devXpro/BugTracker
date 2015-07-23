<?php
/**
 * Created by PhpStorm.
 * User: roma
 * Date: 09.07.15
 * Time: 19:31
 */

namespace BugBundle\Security;

use BugBundle\Entity\Project;
use BugBundle\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;

class ProjectCanManipulateVoter extends BugAbstractVoter
{
    const CAN_MANIPULATE_ISSUE = 'can_manipulate_project';
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
        return array('BugBundle\Controller\ProjectController');
    }

    /**
     * @param User $user
     * @param $obj
     * @param array $attributes
     * @return int
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
