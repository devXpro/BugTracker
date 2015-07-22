<?php
/**
 * Created by PhpStorm.
 * User: roma
 * Date: 21.07.15
 * Time: 18:42
 */

namespace BugBundle\Tests\Unit\Entity;


use BugBundle\Entity\IssueResolution;

class IssueResolutionTest extends \PHPUnit_Framework_TestCase
{
    public function testEntity()
    {
        $label = 'label';
        $issueResolution = new IssueResolution();
        $issueResolution->setLabel($label);

        $this->assertEquals($label, $issueResolution->getLabel());
        $this->assertNull($issueResolution->getId());
        $this->assertEquals($label, $issueResolution->__toString());
    }
}
