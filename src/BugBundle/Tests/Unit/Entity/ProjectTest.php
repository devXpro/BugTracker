<?php
/**
 * Created by PhpStorm.
 * User: roma
 * Date: 21.07.15
 * Time: 18:57
 */

namespace BugBundle\Tests\Unit\Entity;


use BugBundle\Entity\Issue;
use BugBundle\Entity\Project;
use BugBundle\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;

class ProjectTest extends \PHPUnit_Framework_TestCase
{
    public function testEntity()
    {
        $project = new Project();

        $code = '123';
        $creator = new User();
        $label = 'label';
        $summary = 'summary';
        $issue = new Issue();
        $issues = new ArrayCollection();
        $issues->add($issue);
        $member = new User();
        $members = new ArrayCollection();
        $members->add($member);


        $project->setCode($code);
        $project->setCreator($creator);
        $project->setLabel($label);
        $project->setSummary($summary);
        $project->addIssue($issue);
        $project->addMember($member);


        $this->assertEquals($code, $project->getCode());
        $this->assertEquals($creator, $project->getCreator());
        $this->assertEquals($label, $project->getLabel());
        $this->assertEquals($summary, $project->getSummary());
        $this->assertEquals($issues, $project->getIssues());
        $this->assertEquals($members, $project->getMembers());
        $this->assertEquals($label, $project->__toString());
        $project->removeIssue($issue);
        $project->removeMember($member);

        $this->assertNotEquals($issues, $project->getIssues());
        $this->assertNotEquals($members, $project->getMembers());
        $this->assertNull($project->getId());

    }
}
