<?php

namespace BugBundle\EventListener;

use BugBundle\Entity\Project;
use BugBundle\Event\BugEntityEvent;
use BugBundle\Services\IssueActivityInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

class ProjectListener implements ListenerInterface
{

    /** @var TokenStorage */
    private $token;
    /** @var IssueActivityInterface */
    private $activityManager;

    public function __construct(TokenStorage $token, IssueActivityInterface $activityManager)
    {
        $this->token = $token;
        $this->activityManager = $activityManager;
    }

    /**
     * {@inheritdoc}
     */
    public function onPreCreate(BugEntityEvent $event)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function onAfterCreate(BugEntityEvent $event)
    {
        /** @var Project $issue */
        $project = $event->getEntity();
        if (!$this->token->getToken()) {
            return;
        }
        $project->addMember($this->token->getToken()->getUser());
        $event->getEm()->persist($project);
        $event->getEm()->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function onUpdate(BugEntityEvent $event)
    {
    }
}
