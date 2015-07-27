<?php

namespace BugBundle\Tests\Unit\Form\Type;

use BugBundle\Entity\Role;
use BugBundle\Entity\User;
use BugBundle\Form\Type\UserType;
use BugBundle\Tests\EntityTypeStub;
use Symfony\Component\Form\PreloadedExtension;
use Symfony\Component\Form\Test\TypeTestCase;

class UserTypeTest extends TypeTestCase
{
    /**
     * @dataProvider formDataProvider
     * @param User $user
     * @param $formData
     */
    public function testSubmitValidData(User $user, $formData)
    {
        $userType = new UserType();
        $form = $this->factory->create($userType);
        $form->submit($formData);
        $this->assertTrue($form->isSynchronized());
        $this->assertEquals($user, $form->getData());

    }

    public function formDataProvider()
    {
        $user = new User();
        array_walk(
            $this->getRoles(),
            function ($role) use ($user) {
                $user->addRole($role);
            }
        );
        $user->setUsername('userName');
        $user->setPassword('pass');
        $user->setEmail('pass');

        return [
            [
                $user,
                array(
                    'email' => $user->getEmail(),
                    'username' => $user->getUsername(),
                    'roles' => array_keys($this->getRoles()),
                    'password' => $user->getPassword(),
                ),
            ],
        ];
    }

    /**
     * @return array
     */
    protected function getExtensions()
    {
        $stubEntityType = new EntityTypeStub($this->getRoles(), 'bug_user_select_role', array('multiple' => true));
        $ext = new PreloadedExtension(array($stubEntityType->getName() => $stubEntityType), array());

        return array($ext);
    }

    private function getRoles()
    {
        $roles = array();
        for ($i = 1; $i < 3; $i++) {
            $role = new Role();
            $idReflection = new \ReflectionProperty(get_class($role), 'id');
            $idReflection->setAccessible(true);
            $idReflection->setValue($role, $i);
            $role->setRole('role_'.$i);
            $roles[$i] = $role;
        }

        return $roles;
    }
}
