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
    public function trans($string)
    {

        $translator=$this->container->get('translator');
        $string=$translator->trans($string);
        $encoding='UTF8';
        $strlen = mb_strlen($string, $encoding);
        $firstChar = mb_substr($string, 0, 1, $encoding);
        $then = mb_substr($string, 1, $strlen - 1, $encoding);
        return mb_strtoupper($firstChar, $encoding) . $then;
    }
    public function mainMenu(FactoryInterface $factory, array $options)
    {

        $menu = $factory->createItem('root');

        $menu->addChild($this->trans('home'), array('route' => 'index'));

        if ($this->container->get('security.authorization_checker')->isGranted(Role::ROLE_ADMIN))
            $menu->addChild($this->trans('users'), array(
                'route' => 'admin_users_list',

            ));
        $menu->addChild($this->trans('projects'), array(
            'route' => 'projects_list',

        ));


        return $menu;
    }
}