<?php

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
     * @param IssueStatus $oldStatus
     * @param IssueStatus $newStatus
     * @return mixed
     */
    public function markChangeStatusIssue(Issue $issue, IssueStatus $oldStatus, IssueStatus $newStatus);
}
