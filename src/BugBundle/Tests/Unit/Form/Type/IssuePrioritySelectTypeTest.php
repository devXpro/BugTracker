<?php
/**
 * Created by PhpStorm.
 * User: roma
 * Date: 20.07.15
 * Time: 13:31
 */

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
