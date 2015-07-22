<?php
/**
 * Created by PhpStorm.
 * User: roma
 * Date: 21.07.15
 * Time: 18:36
 */

namespace BugBundle\Tests\Unit\Entity;


use BugBundle\Entity\Issue;
use BugBundle\Entity\IssueComment;
use BugBundle\Entity\User;

class IssueCommentTest extends \PHPUnit_Framework_TestCase
{
    public function testEntity()
    {
        $comment = new IssueComment();
        $now = new \DateTime();
        $author = new User();
        $body = 'body';
        $issue = new Issue();


        $comment->setCreated($now);
        $comment->setAuthor($author);
        $comment->setBody($body);
        $comment->setIssue($issue);
        $comment->setCreatedNow();


        $this->assertEquals($now, $comment->getCreated());
        $this->assertEquals($author, $comment->getAuthor());
        $this->assertEquals($body, $comment->getBody());
        $this->assertEquals($issue, $comment->getIssue());
        $this->assertEquals($body, $comment->__toString());
        $this->assertNull($comment->getId());

    }

}
