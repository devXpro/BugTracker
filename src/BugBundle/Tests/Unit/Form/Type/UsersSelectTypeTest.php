<?php

namespace BugBundle\Tests\Unit\Form\Type;

use BugBundle\Form\Type\UsersSelectType;
use BugBundle\Tests\BugTypeTestCase;

class UsersSelectTypeTest extends BugTypeTestCase
{
    public function testSelector()
    {
        $selectType = new UsersSelectType();
        $this->checkSelectors($selectType);
    }
}
