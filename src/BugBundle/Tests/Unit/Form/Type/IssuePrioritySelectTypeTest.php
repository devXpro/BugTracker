<?php

namespace BugBundle\Tests\Unit\Form\Type;

use BugBundle\Form\Type\IssuePrioritySelectType;
use BugBundle\Tests\BugTypeTestCase;

class IssuePrioritySelectTypeTest extends BugTypeTestCase
{
    public function testSelector()
    {
        $selectType = new IssuePrioritySelectType();
        $this->checkSelectors($selectType);
    }
}
