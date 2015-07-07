<?php
/**
 * Created by PhpStorm.
 * User: roma
 * Date: 07.07.15
 * Time: 11:16
 */

namespace BugBundle\EventListener;

use BugBundle\Entity\IssueComment;
use BugBundle\Event\BugEntityEvent;
use BugBundle\Services\IssueCommentActivityInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

class IssueCommentListener implements ListenerInterface
{
    /** @var  TokenStorage */
    private $token;
    private $activityManager;

    public function __construct(TokenStorage $token, IssueCommentActivityInterface $activityManager)
    {
        $this->token = $token;
        $this->activityManager = $activityManager;
    }

    public function onCreate(BugEntityEvent $event)
    {
        //add collaborator is user leave comment
        /** @var IssueComment $issueComment */
        $issueComment = $event->getEntity();
        $issue = $issueComment->getIssue();
        $issue->addCollaborator($this->token->getToken()->getUser());
        $this->activityManager->markCommentIssue($issueComment);
    }

    public function onUpdate(BugEntityEvent $event)
    {

    }
}