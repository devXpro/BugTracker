<?php
/**
 * Created by PhpStorm.
 * User: roma
 * Date: 30.06.15
 * Time: 20:46
 */

namespace BugBundle\Menu;

use BugBundle\Entity\Role;
use Knp\Menu\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerAware;

class Builder extends ContainerAware
{
    public function mainMenu(FactoryInterface $factory, array $options)
    {
        $menu = $factory->createItem('root');

        $menu->addChild('Home', array('route' => 'index'));

        if ($this->container->get('security.authorization_checker')->isGranted(Role::ROLE_ADMIN))
            $menu->addChild('Users', array(
                'route' => 'admin_users_list',

            ));
        $menu->addChild('Registration', array(
            'route' => 'registration_route',

        ));


        return $menu;
    }
}