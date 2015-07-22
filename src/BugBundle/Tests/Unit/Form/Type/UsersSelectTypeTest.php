<?php
/**
 * Created by PhpStorm.
 * User: roma
 * Date: 20.07.15
 * Time: 13:36
 */

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
