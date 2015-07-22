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

    /**
     * @param BugEntityEvent $event
     * @return void
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
     * @param BugEntityEvent $event
     * @return void
     */
    public function onUpdate(BugEntityEvent $event)
    {

    }

    /**
     * @param BugEntityEvent $event
     * @return void
     */
    public function onAfterCreate(BugEntityEvent $event)
    {

    }
}