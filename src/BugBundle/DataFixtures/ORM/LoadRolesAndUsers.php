<?php
/**
 * Created by PhpStorm.
 * User: roma
 * Date: 26.06.15
 * Time: 16:15
 */

namespace BugBundle\DataFixtures\ORM;


use BugBundle\Entity\Role;
use BugBundle\Entity\User;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadRolesAndUsers implements FixtureInterface, ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * {@inheritDoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function load(ObjectManager $manager)
    {

        $roles = array(Role::ROLE_ADMIN, Role::ROLE_USER, Role::ROLE_MANAGER);
        foreach ($roles as $roleName) {
            if (!$role = $manager->getRepository('BugBundle:Role')->findOneBy(array('role' => $roleName)))
                $role = new Role();
            $role->setRole($roleName);

            //add User (User name is a role user)
            $userName = strtolower(str_replace('ROLE_','',$roleName));

            if (!$user = $manager->getRepository('BugBundle:User')->findOneBy(array('username' => $userName)))
                $user = new User();
            $user->addRole($role);
            $user->setUsername($userName);
            $user->setEmail($userName . '@ukr.net');
            $encoder = $this->container->get('security.encoder_factory')->getEncoder($user);
            $user->setPassword($encoder->encodePassword($userName, $user->getSalt())); //User name and password is a same
            $manager->persist($role);
            $manager->persist($user);
        }
        $manager->flush();
    }
}