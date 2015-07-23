<?php
/**
 * Created by PhpStorm.
 * User: roma
 * Date: 07.07.15
 * Time: 11:16
 */

namespace BugBundle\EventListener;

use BugBundle\Entity\Issue;
use BugBundle\Entity\Project;
use BugBundle\Event\BugEntityEvent;
use BugBundle\Services\IssueActivityInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

class ProjectListener implements ListenerInterface
{

    /** @var Issue */
    private $token;
    private $activityManager;

    public function __construct(TokenStorage $token, IssueActivityInterface $activityManager)
    {
        $this->token = $token;
        $this->activityManager = $activityManager;
    }

    public function onPreCreate(BugEntityEvent $event)
    {


    }

    public function onAfterCreate(BugEntityEvent $event)
    {
        /** @var Project $issue */
        $project = $event->getEntity();
        if (!$this->token->getToken()) {
            return;
        }
        $project->addMember($this->token->getToken()->getUser());
    }

    public function onUpdate(BugEntityEvent $event)
    {


    }
}
