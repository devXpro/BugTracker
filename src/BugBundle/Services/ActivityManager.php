<?php
/**
 * Created by PhpStorm.
 * User: roma
 * Date: 07.07.15
 * Time: 16:31
 */

namespace BugBundle\Services;


use BugBundle\Entity\Activity;
use BugBundle\Entity\Issue;
use BugBundle\Entity\IssueComment;
use BugBundle\Entity\IssueStatus;
use BugBundle\Entity\User;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Csrf\Exception\TokenNotFoundException;
use Symfony\Component\Translation\TranslatorInterface;


class ActivityManager implements IssueActivityInterface, IssueCommentActivityInterface
{

    private $trans;
    private $token;

    /**
     * @param TranslatorInterface $translator
     * @param ManagerRegistry $doctrine
     * @param TokenStorageInterface $token
     */
    public function __construct(
        TranslatorInterface $translator,
        ManagerRegistry $doctrine,
        TokenStorageInterface $token
    ) {
        $this->trans = $translator;
        $this->doctrine = $doctrine;
        $this->token = $token;

    }

    /**
     * @param Issue $issue
     */
    public function markCreateIssue(Issue $issue)
    {
        $em = $this->doctrine->getManagerForClass('BugBundle:Activity');
        $activity = new Activity();
        $activity->setType(Activity::TYPE_CREATE_ISSUE);
        $activity->setIssue($issue);
        $this->tokenCheck();
        $activity->setUser($this->token->getToken()->getUser());
        $em->persist($activity);
        $em->flush();
    }

    private function tokenCheck(){
        if(!$this->token->getToken()){
            throw new TokenNotFoundException();
        }
        if(!$this->token->getToken()->getUser()){
            throw new AuthenticationException();
        }
        if(!($this->token->getToken()->getUser() instanceof User))
            throw new EntityNotFoundException();
    }

    /**
     * @param Issue $issue
     * @param IssueStatus $oldStatus
     * @param IssueStatus $newStatus
     */
    public function markChangeStatusIssue(Issue $issue,IssueStatus $oldStatus,IssueStatus $newStatus)
    {
        $em=$this->doctrine->getManagerForClass('BugBundle:Activity');
        $activity = new Activity();
        $activity->setType(Activity::TYPE_CHANGE_STATUS_ISSUE);
        $activity->setIssue($issue);
        $this->tokenCheck();
        $activity->setUser($this->token->getToken()->getUser());
        $activity->setOldStatus($oldStatus);
        $activity->setNewStatus($newStatus);
        $em->persist($activity);
        $em->flush();
    }

    /**
     * @param IssueComment $comment
     * @throws EntityNotFoundException
     */
    public function markCommentIssue(IssueComment $comment)
    {
        $em=$this->doctrine->getManagerForClass('BugBundle:Activity');
        $activity = new Activity();
        $activity->setType(Activity::TYPE_COMMENT_ISSUE);
        $activity->setComment($comment);
        $this->tokenCheck();
        $activity->setUser($this->token->getToken()->getUser());
        if(!($comment->getIssue() instanceof Issue))
            throw new EntityNotFoundException();
        $activity->setIssue($comment->getIssue());
        $em->persist($activity);
    }

    /**
     * @param $type int
     * @return string
     */
    public function getTypeName($type)
    {
        $tr = $this->trans;
        switch ($type) {
            case Activity::TYPE_CREATE_ISSUE:
                return $tr->trans('createNewIssue');
            case Activity::TYPE_CHANGE_STATUS_ISSUE:
                return $tr->trans('changeStatus');
            case Activity::TYPE_COMMENT_ISSUE:
                return $tr->trans('commentIssue');

        }
    }
}