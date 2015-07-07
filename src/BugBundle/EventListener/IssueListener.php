<?php
/**
 * Created by PhpStorm.
 * User: roma
 * Date: 07.07.15
 * Time: 11:16
 */

namespace BugBundle\EventListener;


use BugBundle\Entity\Issue;
use BugBundle\Event\BugEntityEvent;
use BugBundle\Services\IssueActivityInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

class IssueListener implements ListenerInterface
{

    /** @var Issue */
    private $token;
    private $activityManager;

    public function __construct(TokenStorage $token,IssueActivityInterface $activityManager){
        $this->token=$token;
        $this->activityManager=$activityManager;
    }

    public function onCreate(BugEntityEvent $event){
        /** @var Issue $issue */
        $issue=$event->getEntity();
        //Add Creator task to collaborators
        $issue->addCollaborator($this->token->getToken()->getUser());
        $this->activityManager->markCreateIssue($issue);

    }

    public function onUpdate(BugEntityEvent $event){
        /** @var Issue $issue */
        $issue=$event->getEntity();
        $changes=$event->getEm()->getUnitOfWork()->getEntityChangeSet($issue);
        //mark to activity, if status changed
        if(isset($changes['status'])){
            $this->activityManager->markChangeStatusIssue($issue);
        }

    }
}