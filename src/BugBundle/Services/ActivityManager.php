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

    public function __construct(ContainerInterface $container)
    {
        $this->trans = $container->get('translator');
        $this->twig = $container->get('twig');
        $this->em = $container->get('doctrine')->getManager();
    }

    public function markCreateIssue(Issue $issue)
    {
        $activity = new Activity();
        $activity->setType(Activity::TYPE_CREATE_ISSUE);
        $activity->setIssue($issue);
        $message = $this->twig->render('@Bug/Notification/create_issue.html.twig', array('issue' => $issue));
        $activity->setMessage($message);
        $this->em->persist($activity);
    }

    public function markChangeStatusIssue(Issue $issue)
    {
        $activity = new Activity();
        $activity->setType(Activity::TYPE_CHANGE_STATUS_ISSUE);
        $activity->setIssue($issue);
        $message = $this->twig->render('@Bug/Notification/change_status_issue.html.twig', array('issue' => $issue));
        $activity->setMessage($message);
        $this->em->persist($activity);
    }

    public function markCommentIssue(IssueComment $comment)
    {
        $activity = new Activity();
        $activity->setType(Activity::TYPE_COMMENT_ISSUE);
        $activity->setIssue($comment->getIssue());
        $message = $this->twig->render('@Bug/Notification/comment_issue.html.twig', array('comment' => $comment));
        $activity->setMessage($message);
        $this->em->persist($activity);

    }

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