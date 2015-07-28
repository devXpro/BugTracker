<?php

namespace BugBundle\Tests\Unit\Form\Type;

use BugBundle\Form\Type\IssueStatusSelectType;
use BugBundle\Tests\BugTypeTestCase;

class IssueStatusSelectTypeTest extends BugTypeTestCase
{
    public function testSelector()
    {
        $selectType = new IssueStatusSelectType();
        $this->checkSelectors($selectType);
    }
}
