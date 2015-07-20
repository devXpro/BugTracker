<?php
/**
 * Created by PhpStorm.
 * User: roma
 * Date: 20.07.15
 * Time: 13:35
 */

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
