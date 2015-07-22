<?php
/**
 * Created by PhpStorm.
 * User: roma
 * Date: 20.07.15
 * Time: 13:33
 */

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
