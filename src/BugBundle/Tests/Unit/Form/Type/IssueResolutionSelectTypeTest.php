<?php

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
