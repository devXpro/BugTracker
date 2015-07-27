<?php

namespace BugBundle\Tests\Unit\Form\Type;

use BugBundle\Form\Type\IssueSelectType;
use BugBundle\Tests\BugTypeTestCase;

class IssueSelectTypeTest extends BugTypeTestCase
{
    public function testSelector()
    {
        $selectType = new IssueSelectType();
        $this->checkSelectors($selectType);
    }
}
