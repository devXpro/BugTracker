<?php
/**
 * Created by PhpStorm.
 * User: roma
 * Date: 07.07.15
 * Time: 17:31
 */

namespace BugBundle\Services;


use BugBundle\Entity\IssueComment;

interface IssueCommentActivityInterface
{
    public function markCommentIssue(IssueComment $comment);
}