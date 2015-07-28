<?php

namespace BugBundle\EventListener;

use BugBundle\Entity\IssueComment;
use BugBundle\Event\BugEntityEvent;
use BugBundle\Services\IssueCommentActivityInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

class IssueCommentListener implements ListenerInterface
{
    /** @var  TokenStorage */
    private $token;
    /** @var  IssueCommentActivityInterface */
    private $activityManager;

    public function __construct(TokenStorage $token, IssueCommentActivityInterface $activityManager)
    {
        $this->token = $token;
        $this->activityManager = $activityManager;
    }

    /**
     * {@inheritdoc}
     */
    public function onPreCreate(BugEntityEvent $event)
    {
        //add collaborator if user leave comment
        /** @var IssueComment $issueComment */
        $issueComment = $event->getEntity();
        $issue = $issueComment->getIssue();
        $user = $this->token->getToken()->getUser();
        $issue->addCollaborator($user);
        $this->activityManager->markCommentIssue($issueComment);
    }

    /**
     * {@inheritdoc}
     */
    public function onUpdate(BugEntityEvent $event)
    {

    }

    /**
     * {@inheritdoc}
     */
    public function onAfterCreate(BugEntityEvent $event)
    {

    }
}
