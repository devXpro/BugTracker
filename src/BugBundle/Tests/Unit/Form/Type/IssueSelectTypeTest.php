<?php
/**
 * Created by PhpStorm.
 * User: roma
 * Date: 20.07.15
 * Time: 13:32
 */

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
