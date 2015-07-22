<?php
/**
 * Created by PhpStorm.
 * User: roma
 * Date: 20.07.15
 * Time: 15:52
 */

namespace BugBundle\Tests;


use Doctrine\Entity;

trait EntitySetHelper
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
            if (is_scalar($field)) {
                $setter = 'set'.ucfirst($field);
                $entity->$setter($field);
            }
        }

        return $entity;
    }
}