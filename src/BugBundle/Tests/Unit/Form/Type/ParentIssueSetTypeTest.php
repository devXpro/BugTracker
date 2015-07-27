<?php

namespace BugBundle\Tests\Unit\Form\Type;

use BugBundle\Form\Type\ParentIssueSetType;
use BugBundle\Tests\BugTypeTestCase;

class ParentIssueSetTypeTest extends BugTypeTestCase
{
    public function testSelector()
    {
        $selectType = new ParentIssueSetType();
        $this->checkSelectors($selectType);
    }
}
