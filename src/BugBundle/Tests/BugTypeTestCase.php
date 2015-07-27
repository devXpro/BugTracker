<?php

namespace BugBundle\Tests;

use BugBundle\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

abstract class BugTypeTestCase extends TypeTestCase
{

    use EntitySetHelper;

    /**
     * @param $entity
     * @param array $excludeFields
     * @return array
     */
    protected function entityToFormData($entity, $excludeFields = array())
    {
        $excludeFields = array_merge(array('created', 'updated'), $excludeFields);
        $methods = get_class_methods($entity);
        $data = array();
        foreach ($methods as $method) {
            if ((substr($method, 0, strlen('get')) == 'get')) {
                $filedValue = $entity->$method();
                $fieldName = strtolower(substr($method, 3, strlen($method) - 3));
                if ($fieldName == 'id' || !property_exists($entity, $fieldName) || in_array($fieldName, $excludeFields)
                ) {
                    continue;
                }


                if (is_scalar($filedValue) || is_null($filedValue)) {
                    $data[$fieldName] = $filedValue;
                } else {
                    //array or collection
                    if (($filedValue instanceof ArrayCollection) || is_array($filedValue)) {
                        $data[$fieldName] = array();
                        foreach ($filedValue as $subValue) {
                            $data[$fieldName][$subValue->getId()] = $subValue->getId();
                        }
                    } else {
                        //Instance Of Entity
                        $data[$fieldName] = $filedValue->getId();
                    }
                }
            }
        }

        return $data;
    }

    /**
     *
     * Example $paramsSet :
     * array(
     *      array(new User(),new User()),  'some_form_type', array('multiple'=>true)),
     *      array(new Issue(),new Issue()),'some_another_form_type')
     * )
     * @param array $paramsSet
     * @return array
     */
    protected function getEntityStubs(array $paramsSet)
    {
        $result = array();
        foreach ($paramsSet as $params) {
            if (!isset($params[2])) {
                $params[2] = array();
            }
            $stub = new EntityTypeStub($params[0], $params[1], $params[2]);
            $result[$stub->getName()] = $stub;
        }

        return $result;
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


    protected function checkSelectors($obj)
    {
        $this->assertTrue(method_exists($obj, 'getName'));
        $this->assertTrue(method_exists($obj, 'configureOptions'));
        $this->assertTrue(method_exists($obj, 'getParent'));
        /** @var AbstractType $obj */

        $this->assertContains($obj->getParent(), array('entity', 'choice'));
        $this->assertInternalType('string', $obj->getName());
        /** @var OptionsResolver | \PHPUnit_Framework_MockObject_MockObject $optionsResolverMock */
        $optionsResolverMock = $this->getMock('Symfony\Component\OptionsResolver\OptionsResolver');
        $optionsResolverMock->expects($this->once())->method('setDefaults')->with(
            $this->logicalAnd(
                $this->isType('array'),
                $this->logicalOr($this->arrayHasKey('class'), $this->arrayHasKey('choices'))
            )
        );
        $obj->configureOptions($optionsResolverMock);
    }
}
