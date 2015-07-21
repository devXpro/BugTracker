<?php
/**
 * Created by PhpStorm.
 * User: roma
 * Date: 21.07.15
 * Time: 19:22
 */

namespace BugBundle\Tests\Unit\Entity;


use BugBundle\Entity\Role;

class RoleTest extends \PHPUnit_Framework_TestCase
{
    public function testEntity()
    {

        $role = new Role();
        $role->setRole(Role::ROLE_ADMIN);
        $this->assertEquals(Role::ROLE_ADMIN, $role->getRole());
        $this->assertNull($role->getId());
        $this->assertEquals(Role::ROLE_ADMIN, $role->__toString());
    }
}
