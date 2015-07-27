<?php

namespace BugBundle\Tests\Unit\Form\Type;

use BugBundle\Form\Type\UserSelectType;
use BugBundle\Tests\BugTypeTestCase;

class UserSelectTypeTest extends BugTypeTestCase
{
    public function testSelector()
    {
        $selectType = new UserSelectType();
        $this->checkSelectors($selectType);
    }
}
