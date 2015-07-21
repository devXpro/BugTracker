<?php
/**
 * Created by PhpStorm.
 * User: roma
 * Date: 21.07.15
 * Time: 19:25
 */

namespace BugBundle\Tests\Unit\Entity;


use BugBundle\Entity\Role;
use BugBundle\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;

class UserTest extends \PHPUnit_Framework_TestCase
{
    public function testEntity()
    {

        $path = 'asdf';
        $file = $this->getMockBuilder('Symfony\Component\HttpFoundation\File\UploadedFile')->disableOriginalConstructor(
        )->getMock();


        $active = true;
        $email = 'me@ee.ee';
        $pass = 'asdfas';
        $fullName = 'asdfasdf';
        $userName = 'User';
        $user = new User();
        $role = new Role();
        $roles = new ArrayCollection();
        $roles->add($role);


        $user->setAva($file);
        $user->setEmail($email);
        $user->setFullName($fullName);
        $user->setIsActive($active);
        $user->setPassword($pass);
        $user->setPath($path);
        $user->setUsername($userName);
        $user->addRole($role);
        $user->eraseCredentials();

        $this->assertEquals($email, $user->getEmail());
        $this->assertEquals($path, $user->getPath());
        $this->assertEquals($active, $user->getIsActive());
        $this->assertEquals($fullName, $user->getFullName());
        $this->assertEquals($pass, $user->getPassword());
        $this->assertEquals($fullName, $user->__toString());
        $this->assertEquals($userName, $user->getUsername());
        $this->assertEquals($roles->toArray(), $user->getRoles());
        $user->removeRole($role);
        $this->assertNotEquals($roles, $user->getRoles());
        $this->assertNull($user->getId());
        $this->assertNull($user->getSalt());
        $this->assertEquals(
            $user->serialize(),
            serialize(array($user->getId(), $user->getUsername(), $user->getPassword()))
        );

        $user->unserialize(serialize(array(1, 2, 3)));
        $this->assertEquals(1, $user->getId());
        $this->assertEquals(2, $user->getUsername());
        $this->assertEquals(3, $user->getPassword());
        $this->assertContains($path, $user->getAbsolutePath());
        $this->assertContains($path, $user->getWebPath());

        $user->upload();
        $user->setAva(null);
        $user->upload();


    }
}
