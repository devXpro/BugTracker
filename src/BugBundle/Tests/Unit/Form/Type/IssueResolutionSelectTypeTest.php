<?php
/**
 * Created by PhpStorm.
 * User: roma
 * Date: 20.07.15
 * Time: 13:32
 */

namespace BugBundle\Tests\Unit\Form\Type;


use BugBundle\Form\Type\IssueResolutionSelectType;
use BugBundle\Tests\BugTypeTestCase;

class IssueResolutionSelectTypeTest extends BugTypeTestCase
{
    public function testSelector()
    {
        $selectType = new IssueResolutionSelectType();
        $this->checkSelectors($selectType);
    }
}
