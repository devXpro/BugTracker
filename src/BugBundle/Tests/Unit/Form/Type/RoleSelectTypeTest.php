<?php

namespace BugBundle\Tests\Unit\Form\Type;

use BugBundle\Form\Type\RoleSelectType;
use BugBundle\Tests\BugTypeTestCase;

class RoleSelectTypeTest extends BugTypeTestCase
{
    public function testSelector()
    {
        $selectType = new RoleSelectType();
        $this->checkSelectors($selectType);
    }
}
