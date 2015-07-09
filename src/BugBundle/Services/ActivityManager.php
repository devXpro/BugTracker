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
use Symfony\Component\DependencyInjection\ContainerInterface;


class ActivityManager implements IssueActivityInterface, IssueCommentActivityInterface
{

    private $twig;
    private $em;
    private $trans;
    private $token;
    private $router;

    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->trans = $container->get('translator');
        $this->twig = $container->get('twig');
        $this->em = $container->get('doctrine')->getManager();
        $this->token = $container->get('security.token_storage');
        $this->router = $container->get('router');


    }

    /**
     * @param Issue $issue
     */
    public function markCreateIssue(Issue $issue)
    {
        $activity = new Activity();
        $activity->setType(Activity::TYPE_CREATE_ISSUE);
        $activity->setIssue($issue);
        $userName = $this->token->getToken()->getUser()->getAnyName();
        $activity->setVars(array(
            'user' => $userName,
            'issue' => $issue->getIssueFullName(),
            'project' => $issue->getProject()->getLabel(),
            'issue_link' => $this->router->generate('bug_issue_view', array('issue' => $issue->getId()))
        ));
        $this->em->persist($activity);
    }

    /**
     * @param Issue $issue
     */
    public function markChangeStatusIssue(Issue $issue)
    {
        $activity = new Activity();
        $activity->setType(Activity::TYPE_CHANGE_STATUS_ISSUE);
        $activity->setIssue($issue);
        $userName = $this->token->getToken()->getUser()->getAnyName();
        $activity->setVars(array(
            'user' => $userName,
            'status' => $issue->getStatus()->getLabel(),
            'issue' => $issue->getIssueFullName(),
            'issue_link' => $this->router->generate('bug_issue_view', array('issue' => $issue->getId()))
        ));
        $this->em->persist($activity);
        $this->em->flush();
    }

    /**
     * @param IssueComment $comment
     */
    public function markCommentIssue(IssueComment $comment)
    {
        $activity = new Activity();
        $activity->setType(Activity::TYPE_COMMENT_ISSUE);
        $activity->setIssue($comment->getIssue());
        $userName = $this->token->getToken()->getUser()->getAnyName();
        $activity->setVars(array(
            'user' => $userName,
            'comment' => $comment->getBody(),
            'issue' => $comment->getIssue()->getIssueFullName(),
            'issue_link' => $this->router->generate('bug_issue_view', array('issue' => $comment->getIssue()->getId()))
        ));
        $this->em->persist($activity);

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