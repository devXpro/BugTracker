<?php
/**
 * Created by PhpStorm.
 * User: roma
 * Date: 21.07.15
 * Time: 18:50
 */

namespace BugBundle\Tests\Unit\Entity;


use BugBundle\Entity\Activity;
use BugBundle\Entity\Issue;
use BugBundle\Entity\IssueComment;
use BugBundle\Entity\IssueStatus;
use BugBundle\Entity\User;

class ActivityTest extends \PHPUnit_Framework_TestCase
{
    public function testEntity()
    {
        $activity = new Activity();
        $now = new \DateTime('now', new \DateTimeZone('UTC'));
        $issue = new Issue();
        $comment = new IssueComment();
        $newStatus = new IssueStatus();
        $oldStatus = new IssueStatus();
        $notify = true;
        $type = Activity::TYPE_CHANGE_STATUS_ISSUE;
        $user = new User();

        $activity->setCreatedNow();
        $activity->setCreated($now);
        $activity->setIssue($issue);
        $activity->setComment($comment);
        $activity->setOldStatus($oldStatus);
        $activity->setNewStatus($newStatus);
        $activity->setNotified($notify);
        $activity->setType($type);
        $activity->setUser($user);


        $this->assertEquals($now, $activity->getCreated());
        $this->assertEquals($issue, $activity->getIssue());
        $this->assertEquals($comment, $activity->getComment());
        $this->assertEquals($oldStatus, $activity->getOldStatus());
        $this->assertEquals($newStatus, $activity->getNewStatus());
        $this->assertEquals($notify, $activity->getNotified());
        $this->assertEquals($type, $activity->getType());
        $this->assertEquals($user, $activity->getUser());
        $this->assertNull($activity->getId());
    }
}
