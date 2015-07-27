<?php

namespace BugBundle\EventListener;

use BugBundle\Entity\Issue;
use BugBundle\Event\BugEntityEvent;
use BugBundle\Services\IssueActivityInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

class IssueListener implements ListenerInterface
{

    /** @var Issue */
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
        /** @var Issue $issue */
        $issue = $event->getEntity();
        //Add Creator task to collaborators
        if (!$this->token->getToken()) {
            return;
        }
        $issue->addCollaborator($issue->getAssignee());
        $issue->addCollaborator($this->token->getToken()->getUser());
        $this->activityManager->markCreateIssue($issue);
    }

    /**
     * {@inheritdoc}
     */
    public function onUpdate(BugEntityEvent $event)
    {
        /** @var Issue $issue */
        $issue = $event->getEntity();
        $changes = $event->getEm()->getUnitOfWork()->getEntityChangeSet($issue);
        //mark to activity, if status changed
        if (isset($changes['status'])) {
            $this->activityManager->markChangeStatusIssue($issue, $changes['status'][0], $changes['status'][1]);
        }
        if (isset($changes['assignee'])) {
            $issue->addCollaborator($issue->getAssignee());
            $event->getEm()->flush();
        }
    }
}
