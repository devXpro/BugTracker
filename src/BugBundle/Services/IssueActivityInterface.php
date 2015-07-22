<?php
/**
 * Created by PhpStorm.
 * User: roma
 * Date: 07.07.15
 * Time: 17:30
 */

namespace BugBundle\Services;


use BugBundle\Entity\Issue;
use BugBundle\Entity\IssueStatus;

/**
 * Interface of Activity Manager for IssueCommentListener
 * Interface IssueActivityInterface
 * @package BugBundle\Services
 */
interface IssueActivityInterface
{
    /**
     * @param Issue $issue
     * @return void
     */
    public function markCreateIssue(Issue $issue);

    /**
     * @param Issue $issue
     * @return void
     */
    public function markChangeStatusIssue(Issue $issue, IssueStatus $oldStatus, IssueStatus $newStatus);
}