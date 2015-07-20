<?php
/**
 * Created by PhpStorm.
 * User: roma
 * Date: 20.07.15
 * Time: 13:34
 */

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
