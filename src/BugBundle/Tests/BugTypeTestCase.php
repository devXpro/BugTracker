<?php
/**
 * Created by PhpStorm.
 * User: roma
 * Date: 18.07.15
 * Time: 14:44
 */

namespace BugBundle\Tests;


use BugBundle\Entity\User;
use Doctrine\Entity;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

abstract class BugTypeTestCase extends TypeTestCase
{
    /**
     * @param $entityName
     * @param $fields
     * @param int $setsQuantity
     * @return Entity[]
     */
    protected function getEntitySet($entityName, $fields, $setsQuantity = 3)
    {
        $result = array();
        for ($i = 1; $i < $setsQuantity; $i++) {
            $entity = new $entityName();
            $idReflection = new \ReflectionProperty(get_class($entity), 'id');
            $idReflection->setAccessible(true);
            $idReflection->setValue($entity, $i);
            foreach ($fields as $field) {
                $setter = 'set'.ucfirst($field);
                $entity->$setter($field.'_'.$i);
            }

            $result[$i] = $entity;
        }

        return $result;
    }

    /**
     * @param $entityName
     * @param $fields
     * @param bool|false $withoutId
     * @return mixed
     */
    protected function getEntity($entityName, $fields, $withoutId = false)
    {
        $entity = new $entityName();
        if (!$withoutId) {
            $idReflection = new \ReflectionProperty(get_class($entity), 'id');
            $idReflection->setAccessible(true);
            $idReflection->setValue($entity, 777);
        }
        foreach ($fields as $field) {
            $setter = 'set'.ucfirst($field);
            $entity->$setter($field);
        }

        return $entity;
    }


    protected function entityToFormData($entity){
        $methods=get_class_methods($entity);
        foreach($methods as $menthod){

        }
    }

    /**
     * @param User $user
     * @return \PHPUnit_Framework_MockObject_MockObject|TokenStorageInterface
     */
    protected function getTokenStorageWithUserMock(User $user)
    {
        $token = $this->getMock('Symfony\Component\Security\Core\Authentication\Token\TokenInterface');
        $token->expects($this->any())->method('getUser')->will($this->returnValue($user));
        /** @var TokenStorageInterface | \PHPUnit_Framework_MockObject_MockObject $tokenStorage */
        $tokenStorage = $this->getMock(
            'Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface'
        );
        $tokenStorage->expects($this->once())->method('getToken')->will($this->returnValue($token));

        return $tokenStorage;
    }
}