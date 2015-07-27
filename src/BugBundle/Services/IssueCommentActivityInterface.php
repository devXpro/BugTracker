<?php

namespace BugBundle\Services;

use BugBundle\Entity\IssueComment;

/**
 * Interface of Activity Manager for IssueListener
 * Interface IssueCommentActivityInterface
 * @package BugBundle\Services
 */
interface IssueCommentActivityInterface
{
    /**
     * @param IssueComment $comment
     * @return mixed
     */
    public function markCommentIssue(IssueComment $comment);
}
