<?php
/**
 * Created by PhpStorm.
 * User: roma
 * Date: 07.07.15
 * Time: 17:31
 */

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
     * @return void
     */
    public function markCommentIssue(IssueComment $comment);
}