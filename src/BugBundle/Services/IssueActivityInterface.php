<?php
/**
 * Created by PhpStorm.
 * User: roma
 * Date: 07.07.15
 * Time: 17:30
 */

namespace BugBundle\Services;


use BugBundle\Entity\Issue;


interface IssueActivityInterface
{
    public function markCreateIssue(Issue $issue);

    public function markChangeStatusIssue(Issue $issue);
}